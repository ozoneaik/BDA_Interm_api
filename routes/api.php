<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\FarmersController;
use App\Http\Controllers\API\FarmsController;
use App\Http\Controllers\API\HomeController;
use App\Http\Controllers\API\LongansController;
use App\Http\Controllers\API\NotificationController;
use App\Http\Controllers\API\PackagesController;
use App\Http\Controllers\API\SettingController;
use App\Http\Controllers\API\UsersController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\WebApplicationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('login_line', [AuthController::class, 'loginWithLine']);
Route::middleware('auth:sanctum')->group(function () {
    Route::get('me', [AuthController::class, 'profile']);
    Route::get('logout', [AuthController::class, 'logout']);
    Route::resource('users', UsersController::class);
    Route::post('users/{id}/approve', [UsersController::class, 'approveOrRejectUser']);
    Route::get('home', [HomeController::class, 'home']);
    Route::resource('farms', FarmsController::class);
    Route::post('farmers/{id}/update', [FarmersController::class, 'update'])->where('id', '\d+');
    Route::resource('farmers', FarmersController::class);
    Route::get('farmers/{id}/check_max_package', [FarmersController::class, 'checkMaxPackage'])->where('id', '\d+');
    Route::post('longans/{id}/update', [LongansController::class, 'update'])->where('id', '\d+');
    Route::resource('longans', LongansController::class);
    Route::get('setting', [SettingController::class, 'index']);
    Route::post('setting', [SettingController::class, 'store']);
    Route::get('package', [PackagesController::class, 'index']);
    Route::post('package', [PackagesController::class, 'buyPackage']);
    Route::post('package/approve', [PackagesController::class, 'approveOrRejectPackage']);
    Route::resource('notifications', NotificationController::class);
    Route::get('count_notification', [NotificationController::class, 'countNotifications']);

    //application API
    Route::post('add-app', [WebApplicationController::class, 'index']);
    Route::get('get_apps', [WebApplicationController::class, 'getApps']);
    Route::post('status/{id}',[WebApplicationController::class, 'changeStatusApp']);
    Route::delete('delete_app/{id}', [WebApplicationController::class, 'destroy']);
    Route::get('get-app-detail/{id}',[WebApplicationController::class,'appDetail']);
    Route::post('edit_app/{id}',[WebApplicationController::class,'editApp']);
});


Route::get('app_image', [WebApplicationController::class, 'getImage']);

/*Route::middleware(['auth:sanctum', 'abilities:farmer'])->group(function (){
    Route::get('farms', [FarmsController::class, 'index']);
});*/
Route::get('longans_image',[LongansController::class, 'getImage']);
Route::get('farm_image',[FarmsController::class, 'getImage']);
Route::get('user_image',[FarmersController::class, 'getImage']);
