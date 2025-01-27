<?php

namespace App\Http\Controllers\API\Auth;

use stdClass;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /**
     * Create User
     * @param Request $request
     * @return User
    */
    public function register(Request $request)
    {
        try {

            $validateUser = Validator::make($request->all(), [
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

            $input = $request->all();
            $input['password'] = bcrypt($input['password']);
            $user = User::create($input);
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

    /**
     * Login The User
     * @param Request $request
     * @return User
    */
    public function login(Request $request)
    {
        try {
            $validateUser = Validator::make($request->all(), [
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
            ->where('email', $request->identifier)
            ->orwhere('phone', $request->identifier)
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


            if (Hash::check($request->password, $user->password)) {

                Auth::login($user);

                return response()->json([
                    'success' => true,
                    'message' => 'User Logged In Successfully',
                    'access_token' => $user->createToken("API TOKEN")->plainTextToken,
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

    /**
     * LogOut The User
     * @param Request $request
     * @return User
     */
    public function logOut(Request $request)
    {
        auth()->user()->tokens()->delete();
        return response()->json([
            'success' => true,
            'message' => 'You have successfully logged out and the token was successfully deleted'
        ],200);
    }
}
