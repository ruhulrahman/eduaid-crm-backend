<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\V1\Admin\AjaxController;
// use App\Http\Controllers\API\V1\Admin\AuthController;

// api/v1/admin
// Route::post('sign_up', [AuthController::class, 'sign_up'])->name('sign_up');
// Route::post('sign_in', [AuthController::class, 'sign_in'])->name('sign_in');
// Route::post('logout', [AuthController::class, 'logout'])->name('logout');
// Route::post('verify_email', [AuthController::class, 'verify_email'])->name('verify_email');

//route: api/v1/admin/ajax/
Route::middleware('auth:sanctum')->group(function(){
    Route::get('ajax/{name}', [AjaxController::class, 'get'])->name('ajax-get');
    Route::post('ajax/{name}', [AjaxController::class, 'post'])->name('ajax-post');
});

