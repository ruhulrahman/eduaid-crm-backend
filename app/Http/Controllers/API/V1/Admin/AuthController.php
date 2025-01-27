<?php

namespace App\Http\Controllers\API\V1\Admin;

use stdClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Vinkla\Hashids\Facades\Hashids;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

	public function sign_up(Request $req){

		try {

            $validateUser = Validator::make($req->all(), [
                'name' => 'required',
                'email' => 'required|email|unique:users|max:255',
                'password' => 'required',
                'c_password' => 'required|same:password',
                'phone' => 'required',
            ]);

            if ($validateUser->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 422);
            }

            $input = $req->all();
            $input['password'] = bcrypt($input['password']);
            $user = model('User')::create($input);
            $success['name'] =  $user->name;

            return response()->json([
                'success' => true,
                'message' => 'User Created Successfully',
                'data'  => $user
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ], 500);
        }

	}

	public function sign_in(Request $req){

		try {
            $validateUser = Validator::make($req->all(), [
                'identifier' => 'required|max:255',
                'password' => 'required'
            ], [
                'identifier.required' => 'Email or phone is required',
                'password.required' => 'Password is required',
            ]);

            if($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 422);
            }

            $user = model('User')::with('role')
            // ->where('user_type', 'admin')
            ->where('email', $req->identifier)
            ->orwhere('phone', $req->identifier)
            ->first();

            if (!$user) {

                $errors = new stdClass;
                $errors->identifier = [
                    "The email or phone is not match with our records."
                ];

                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $errors
                ], 422);
            }

            $permissionIds = model('PermissionRole')::where('role_id', $user->role_id)->pluck('permission_id');
            $user->permission_codes = model('Permission')::whereIn('id', $permissionIds)->pluck('code');
            $permission_codes = model('Permission')::whereIn('id', $permissionIds)->pluck('code');

            if (Hash::check($req->password, $user->password)) {

                Auth::login($user);

                return response()->json([
                    'success' => true,
                    'message' => 'User Logged In Successfully',
                    'access_token' => $user->createToken("api_token")->plainTextToken,
                    'user' => $user,
                    'user_permissions' => $permission_codes,
                    'token_type' => 'Bearer'
                ], 200);
            } else {
                $errors = new stdClass;
                $errors->password = [
                    "The password is not match with our records."
                ];

                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $errors
                ], 422);
            }

        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ], 500);
        }

	}

	public function verify_email(Request $req){

		if(empty($req->token)){
			return res_msg('Sorry, invalid email verification token.', 403);
		}

		$hash_ids=new Hashids('email_verification_link_hash', 64);

		$dHash=$hash_ids->decode($req->token);

		if(empty($dHash[0])){
			return res_msg('Sorry, invalid email verification token.', 403);
		}else{
			$user_id=$dHash[0];
		}

		$auth_user= model('User')::where('id', $user_id)->first();

		if($auth_user){
			model('User')::where('id', $user_id)->update([
				'email_verified_at'=> date("Y-m-d h:i:s")
			]);

			$err_txt='Given credentials do not match with our records';

			if(empty($auth_user)) return res_msg($err_txt, 422); //unauthorised http status code 422;

			//if(empty($user->company)) return res_msg($err_txt, 422);

			$sanctum_token=$auth_user->createToken('client_api_login_token');

			return res_msg('Account verified successfully!.', 200, [
				'api_token'=>$sanctum_token->plainTextToken,
				'auth_user'=>$auth_user,
				'permission_disable'=>config('permission.disable_role_permission', FALSE),
				'company'=>$auth_user->company
			]);
		}else{
			return res_msg('Sorry, failed to verify this email. Please try again later.', 403);
		}

	}

	public function logout(Request $req){

		auth()->user()->tokens()->delete();
        return response()->json([
            'success' => true,
            'message' => 'You have successfully logged out'
        ],200);

	}

    public function set_new_password(Request $req){
        $validator= Validator::make($req->all(), [
            'email'=>'required|email|exists:password_resets,email',
            'password'=>'required|string|min:8|max:30|required_with:confirm_password|same:confirm_password',
            'confirm_password'=>'required|string|min:8|max:30',
            'password_reset_token'=>'required'
        ]);

        if($validator->fails()){
            $errors=$validator->errors()->all();
            return response(['msg'=>$errors[0]], 422);
        }

        $password_reset = DB::table('password_resets')->where(
            'email', $req->email
        )->orderBy('created_at', 'DESC')->first();

        $user = model('User')::where('email', $req->email)->first();

        if(empty($user))return response(['msg'=>'Invalid user!.'], 403);


        if (Hash::check($req->password_reset_token, $password_reset->token)) {

            $user->password = Hash::make($req->password);
            $user->update();

            DB::table('password_resets')->where('email', $user->email)->delete();

			// Mail::to($user->email)->send(new \App\Mail\AfterPasswordResetEmail($user));

            return response(['msg'=>'New password set successfully!.'], 200);
        }else{
            return response(['msg'=>'Invalid reset password token!.'], 403);
        }


    }

    public function send_reset_link(Request $req){

        $validator= Validator::make($req->all(), [
            'email'=>'required|email|exists:users,email'
        ]);

        if($validator->fails()){

            $errors=$validator->errors()->all();
            return response(['msg'=>$errors[0]], 422);

        }

        $user = model('User')::where([
            'email'=>$req->email,
        ])->first();

        if(empty($user)) return res_msg("Given email address do not match with our records.", 422);

		// Mail::to($user->email)->send(new \App\Mail\ResetPasswordLink($user));

        return res_msg('A password reset link has been sent to your registered email inbox.', 200);
    }
}
