<?php

use App\Http\Controllers\Auth\AuthEmployeeController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\RoleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->group(function () {
    Route::middleware('isAdmin')->group(function () {
        Route::post('add-role', [RoleController::class, 'store']);
        Route::post('add-employee', [EmployeeController::class, 'store']);
        Route::get('all-employees', [EmployeeController::class, 'index']);
        Route::match(['put', 'patch'], 'update-employee/{uuid}', [EmployeeController::class, 'update']);
    });
    Route::get('show-employee/{uuid}', [EmployeeController::class, 'show']);
    Route::get('logout-employee', [AuthEmployeeController::class, 'logout']);
});
Route::post('login-employee', [AuthEmployeeController::class, 'login']);