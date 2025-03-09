<?php

use App\Http\Controllers\LibraryController;
use Illuminate\Support\Facades\Route;

Route::post('book', [LibraryController::class, 'addBook']);
Route::post('member', [LibraryController::class, 'addMember']);
