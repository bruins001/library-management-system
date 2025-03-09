<?php

use App\Http\Controllers\LibraryController;
use Illuminate\Support\Facades\Route;

Route::post('books', [LibraryController::class, 'addBook']);
Route::post('member', [LibraryController::class, 'addMember']);
Route::get('books/available', [LibraryController::class, 'listAvailableBooks']);
Route::get('books/borrowed', [LibraryController::class, 'listBorrowedBooks']);
Route::put('books/borrow', [LibraryController::class, 'borrowBook']);
