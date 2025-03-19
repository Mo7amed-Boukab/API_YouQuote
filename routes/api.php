<?php

use App\Http\Controllers\QuoteController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;


Route::middleware('auth:sanctum')->group(function () {
   Route::apiResource('/quotes',QuoteController::class);
   Route::get('/quotes/random/{quote}', [QuoteController::class, 'randomQuote']);
   Route::get('/quotes/popular/{quote}', [QuoteController::class, 'getPopularQuotes']);
   Route::get('/quotes/filter/{quote}', [QuoteController::class, 'filterByWord']);
});

Route::post('/register',[UserController::class,'register']);
Route::post('/login',[UserController::class,'login']);
Route::post('/logout',[UserController::class,'logout'])->middleware('auth:sanctum');;

