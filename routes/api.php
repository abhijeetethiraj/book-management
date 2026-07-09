<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\BorrowController;


Route::get('/books', [BookController::class, 'index']);

Route::get('/books/search', [BookController::class, 'search']);

Route::get('/books/{id}', [BookController::class, 'show']);

Route::post('/books', [BookController::class, 'store']);

Route::post('/borrow', [BorrowController::class, 'borrow']);

Route::post('/return/{id}', [BorrowController::class, 'returnBook']);

Route::get('/borrowed', [BorrowController::class, 'borrowedBooks']);