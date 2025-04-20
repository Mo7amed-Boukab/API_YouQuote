<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\QuoteController;
use App\Http\Controllers\TagController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;


Route::middleware('auth:sanctum')->group(function () {
   Route::apiResource('/quotes',QuoteController::class);

   Route::get('/quotes/random/{quote}', [QuoteController::class, 'randomQuote']);
   Route::get('/quotes/popular/{quote}', [QuoteController::class, 'getPopularQuotes']);
   Route::get('/quotes/filter/{quote}', [QuoteController::class, 'filterByWord']);

   Route::post('/categories/add', [CategoryController::class, 'store']);
   Route::post('/tags/add', [TagController::class, 'store']);

});

Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/tags', [TagController::class, 'index']);

Route::post('/register',[AuthController::class,'register']);
Route::post('/login',[AuthController::class,'login']);
Route::post('/logout',[AuthController::class,'logout'])->middleware('auth:sanctum');;

