<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\SessionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::group(['prefix'=>'v1'],function(){
    Route::post('register',[AuthController::class,'register']);
    Route::post('login',[AuthController::class,'login']);
    Route::post('logout',[AuthController::class,'logout'])->middleware('auth:sanctum');
    Route::post('guest-translation',[SessionController::class,'guestTranslation']);
    Route::group(['middleware'=>['auth:sanctum']],function(){
        Route::put('update-password',[AuthController::class,'updatePassword']);
    });
});




