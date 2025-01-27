<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Auth\RegisterController;
use App\Http\Controllers\API\Auth\ForgotPasswordController;
use App\Http\Controllers\API\UserManagement\PermissionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::get('test', function(){
    return ['msg'=>'Testing Eduaid api'];
});

/** Authentication Routes.... */
Route::post('register', [RegisterController::class, 'register']);
Route::post('login', [RegisterController::class, 'login']);

/**Forgot password with Reset Routes... */
Route::post('send-email', [ForgotPasswordController::class, 'sendEmail']);
Route::post('change-password', [ForgotPasswordController::class, 'changePassword']);
Route::get('password-reset-email/{token}', [ForgotPasswordController::class, 'verfiy_reset_password_token']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('logout', [RegisterController::class, 'logOut']);
});



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([
	'as'=>'api.v1.',
	'prefix'=>'v1',
	'namespace'=>'Api\V1'
], function(){

    Route::prefix('admin')->namespace('Admin')->as('admin.')->group(
	    __DIR__.'/api/admin.php'
	);

    Route::prefix('mobile')->namespace('Mobile')->as('mobile.')->group(
	    __DIR__.'/api/mobile.php'
	);

	// Route::get('common-ajax/{name}', [CommonAjaxController::class, 'get'])->name('common-ajax-get');
	// Route::post('common-ajax/{name}', [CommonAjaxController::class, 'post'])->name('common-ajax-post');

});

