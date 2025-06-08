<?php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\API\SessionController;
use App\Http\Controllers\HomePageController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [HomePageController::class,'index'])->name('home');
Route::get('/aboutus', [HomePageController::class,'aboutus']);
Route::get('/ourservices', [HomePageController::class,'ourservices']);
Route::get('/signtotext', [SessionController::class,'signtotext'])->name('signtotext');

//signIn signUp
Route::get('/register',function(){
    return Inertia::render('Register');
});
Route::get('/login',function(){
    return Inertia::render('Login');
});
Route::post('/register',[AuthController::class,'register'])->name('register');
Route::post('/login',[AuthController::class,'login'])->name('login');
Route::post('/logout',[AuthController::class,'logout'])->middleware('auth:sanctum')->name('logout');


Route::post('/generate-speech',[SessionController::class,'generateSpeech'])->name('generate-speech');
