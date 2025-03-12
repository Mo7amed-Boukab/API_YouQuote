<?php

use App\Http\Controllers\QuoteController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('/quotes',QuoteController::class);
Route::get('/quotes/random/{quote}', [QuoteController::class, 'randomQuote']);
Route::get('/quotes/popular/{quote}', [QuoteController::class, 'getPopularQuotes']);
Route::get('/quotes/filter/{quote}', [QuoteController::class, 'filterByWord']);



