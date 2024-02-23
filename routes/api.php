<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\AppointmentController;

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
Route::get('/users', [AuthController::class, 'index']);
Route::put('/users/{userID}', [AuthController::class, 'update']);
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);
// Route::get('/users/{userId}/roles-permissions', [RoleController::class, 'getUserRolesAndPermissions']);
Route::get('/roles', [RoleController::class, 'index']);
Route::post('/roles', [RoleController::class, 'store']);
Route::delete('/roles/{role}', [RoleController::class, 'destroy']);
Route::put('/roles/{role}', [RoleController::class, 'update']);

Route::get('/permissions', [PermissionController::class, 'index'])->name('permissions');
Route::post('/permissions', [PermissionController::class, 'store'])->name('permissions');
Route::put('/permissions/{permission}', [PermissionController::class, 'update']);
Route::delete('/permissions/{permission}', [PermissionController::class, 'destroy']);

Route::post('/appointment', [AppointmentController::class, 'store']);
Route::get('/appointment', [AppointmentController::class, 'show']);

Route::get('/doctors', [AuthController::class, 'doctors']);
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

});