<?php

use App\Http\Controllers\ApiController;
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

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

// API Route

Route::post('/register', [ApiController::class, "register"]);
Route::post('/login', [ApiController::class, "login"]);


Route::group([
    "middleware" => ["auth:sanctum"]
], function (){
    Route::get('/profile', [ApiController::class, "profile"]);
    Route::get('/logout', [ApiController::class, "logout"]);
    Route::put('/profile/update', [ApiController::class, "update"]);
    Route::delete('/profile/delete', [ApiController::class, "delete"]);
});