<?php

namespace App\Mail;

use Carbon\Carbon;
use Hashids\Hashids;
use Illuminate\Support\Str;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\DB;
use Illuminate\Queue\SerializesModels;

class ResetPasswordLinkMobile extends Mailable{

    use Queueable, SerializesModels;

    public $user;
    public $subject;
    public $company_address;
    public $header_title;
    public $reset_password_url, $token;

    public function __construct($user){

        $this->user = $user;
		$this->subject = "Reset Password for E-Tax Book";
		$this->header_title = "Password Reset";
		// $this->company_address = getCompanyFullAddress($user->company);
		// $this->company_address = getCompanyFullAddress($user->company);

        // $token = Str::random(4);
        $token = rand(1001, 9999);
        $this->token = $token;

        DB::table('password_resets')->where('email', $user->email)->delete();

        DB::table('password_resets')->insert([
            'email'=>$user->email,
            // 'token'=> bcrypt($token),
            'token'=> $token,
            'created_at'=> Carbon::now(),
        ]);

		$this->reset_password_url = config('portal.base_url').'/#/reset-password/'.$token;

    }

    public function build(){

        return $this->subject($this->subject)->view('mail.mobile.reset_password_link');

    }
}
