<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Models\PasswordReset;
use Illuminate\Mail\Message;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Validator;

class ForgotPasswordController extends Controller
{
    /**
     * @queryParams Email send otp The User
     * @param Request $request
     * @return User
     */
    public function sendEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $email = $request->email;

        // Check User's Email Exists or Not
        $user = User::where('email', $email)->first();

        if(!$user){
            return response([
                'message'=>'Email doesnt exists',
                'status'=>'failed'
            ], 404);
        }

        // Generate Token
        // $token = Str::random(60);

        // // Saving Data to Password Reset Table
        // PasswordReset::create([
        //     'email'=>$email,
        //     'token'=>$token,
        //     'created_at'=>Carbon::now()
        // ]);

        Mail::to($user->email)->send(new \App\Mail\ResetPasswordLink($user));

        // Sending EMail with Password Reset View
        // Mail::send('mail.forget_password_reset', ['token'=>$token], function(Message $message)use($email){
        //     $message->subject('Reset Your Password');
        //     $message->to($email);
        // });
        return response([
            'success' => true,
            'message'=>'Password reset email sent. Please check your email to verify your account.'
        ], 200);
    }

    /**
     * @queryParams email get token wise
     * @param Request $request
     * @return User
    */
    public function verfiy_reset_password_token(Request $request, $token)
    {
        $passwordreset = PasswordReset::where('token', $token)->first();

        if (!$passwordreset) {
            return response()->json([
                'message'=>'Token is Invalid or Expired',
                'status'=>'failed'
            ], 422);
        } else {
            return response()->json([
                'success' => true,
                'message'=>'Your reset password token has been verified successfully.',
                'data'=>$passwordreset
            ], 201);
        }
    }

    /**
     * @queryParams change the password
     * @param Request $request
     * @return User
    */
    public function changePassword(Request $request)
    {
        $passwordResetModel = PasswordReset::where('email', $request->email)->first();

        if (!$passwordResetModel) {
            return response()->json([
                'success' => false,
                'message' => 'Password reset email not found. Please, resend it.'
            ]);
        }

        $validator = Validator::make($request->all(), [
            'new_password'  => 'required|min:6',
            'password_confirmation'  => 'required|min:6|same:new_password',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors()
            ]);
        }

        // return $passwordResetModel->email;
        $model = User::where('email', $passwordResetModel->email)->first();
        // return $updateData;
        $model->password = bcrypt($request->new_password);
        $model->update();
        // previous email data deleted
        if ($model->update()) {
            $passwordResetModel->delete();
        }
        return response()->json([
            'success' => true,
            'message' => 'Password change successfully.'
        ], 200);
    }
}
