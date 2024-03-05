<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\Api\UserController as UsersController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RegisterController;
use App\Http\Controllers\Api\PasswordResetController;
use App\Http\Controllers\Api\FoodController;
use App\Http\Controllers\Auth\ResetPasswordController;

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

Auth::routes();

Route::post('register', [RegisterController::class, 'register'])->name('register');
Route::post('login', [RegisterController::class, 'login'])->name('login');
Route::any('/send_pass_email', [PasswordResetController::class, 'send_reset_pass_email']);
Route::any('/reset/{token}', [PasswordResetController::class, 'reset_pass']);
Route::post('/reset-password/{token}', [PasswordResetController::class, 'reset']);

// protected route
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('logout', [RegisterController::class, 'logout'])->name('logout');
    Route::any('/get_user', [UserController::class, 'show']);
    Route::any('/update_profile/{id}', [UserController::class, 'update']);
    Route::any('/update_profile', [UserController::class, 'update_profile']);
    Route::any('/get_user_list', [UsersController::class, 'index'])->name('get_user_list');
    Route::any('/get_food_request_list', [FoodController::class, 'get_food_request_list']);
    Route::any('/get_my_food_request_list/{user_id}', [FoodController::class, 'get_my_food_request_list']);
    Route::any('/create_food_request', [FoodController::class, 'create']);
    Route::any('/edit_food_request/{id}', [FoodController::class, 'edit']);
    Route::any('/delete_food_request/{id}', [FoodController::class, 'destroy']);

    Route::any('/get_food_donate_list', [FoodController::class, 'get_food_donate_list']);
    Route::any('/get_my_food_donate_list/{user_id}', [FoodController::class, 'get_my_food_donate_list']);
    Route::any('/get_food_history', [FoodController::class, 'get_food_history']);
    Route::any('/create_edit_food', [FoodController::class, 'create_edit_food']);
    Route::any('/update_donate_food/{id}', [FoodController::class, 'update_donate_food']);
    Route::any('/accept_food_request', [FoodController::class, 'accept_food_request']);
    Route::any('/donee_found/{id}', [FoodController::class, 'donee_found']);
    Route::any('/show_food/{id}', [FoodController::class, 'show']);
    Route::any('/get_food_waste_history', [FoodController::class, 'get_food_waste_history']);

    Route::any('/user_dashboard/{id}', [UsersController::class, 'user_dashboard']);
    Route::any('/admin_dashboard', [UsersController::class, 'admin_dashboard']);
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
