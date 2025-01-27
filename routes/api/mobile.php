<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\V1\Mobile\AjaxController;
use App\Http\Controllers\API\V1\Mobile\AuthController;

// api/v1/mobile
Route::post('check_email_exist', [AuthController::class, 'check_email_exist'])->name('check_email_exist');
Route::post('check_phone_exist', [AuthController::class, 'check_phone_exist'])->name('check_phone_exist');
Route::post('sign_up', [AuthController::class, 'sign_up'])->name('sign_up');
Route::post('sign_in', [AuthController::class, 'sign_in'])->name('sign_in');
Route::post('logout', [AuthController::class, 'logout'])->name('logout');
Route::post('send_password_reset_link', [AuthController::class, 'send_password_reset_link'])->name('send_password_reset_link');
Route::post('verify_email_for_password_reset', [AuthController::class, 'verify_email_for_password_reset'])->name('verify_email_for_password_reset');
Route::post('set_new_password', [AuthController::class, 'set_new_password'])->name('set_new_password');
Route::post('verify_email', [AuthController::class, 'verify_email'])->name('verify_email');

Route::post('web_social_login/{provider}', [AuthController::class, 'SocialSignup']);
Route::post('mobile_social_login/{provider}', [AuthController::class, 'mobleSocialSignup']);

//route: api/v1/mobile/ajax/
Route::middleware('auth:sanctum')->group(function(){
    Route::get('ajax/{name}', [AjaxController::class, 'get'])->name('ajax-get');
    Route::post('ajax/{name}', [AjaxController::class, 'post'])->name('ajax-post');
});

Route::get('terms_and_conditions_page_list', [AuthController::class, 'terms_and_conditions_page_list']);
Route::post('send_push_notificaiton', [AuthController::class, 'send_push_notificaiton']);
