<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/home',[HomeController::class,'index'])->name('home');

Route::get('/danh-muc/{destinationId}/{slug}',[CategoryController::class,'index'])->name('category.index');
