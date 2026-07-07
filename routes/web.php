<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BookController;


Route::get('/', function () {
    return view('welcome');
});
Route::get('/books',[BookController::class,'index']);

Route::get('/books/search', [BookController::class, 'search']);
Route::get('/books/{id}', [BookController::class, 'show']);


