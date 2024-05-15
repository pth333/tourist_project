<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DestinationController;

use App\Http\Controllers\SliderController;
use App\Http\Controllers\TourController;

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});



Route::middleware('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
    // Category
    Route::prefix('/categories')->group(function () {
        Route::get('/', [CategoryController::class, 'index'])->name('categories.index');
        Route::post('/store', [CategoryController::class, 'store'])->name('categories.store');
        Route::get('/edit/{id}', [CategoryController::class, 'edit'])->name('categories.edit');
        Route::put('/update', [CategoryController::class, 'update'])->name('categories.update');
        Route::get('/delete/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy');
    });

    // Destination
    Route::prefix('/destination')->group(function () {
        Route::get('/', [DestinationController::class, 'index'])->name('destination.index');
        Route::post('/store', [DestinationController::class, 'store'])->name('destination.store');
        Route::get('/edit/{id}', [DestinationController::class, 'edit'])->name('destination.edit');
        Route::put('/update', [DestinationController::class, 'update'])->name('destination.update');
        Route::get('/delete/{id}', [DestinationController::class, 'destroy'])->name('destination.destroy');
    });

    // Tour
    Route::prefix('/tour')->group(function () {
        Route::get('/', [TourController::class, 'index'])->name('tour.index');
        Route::post('/store', [TourController::class, 'store'])->name('tour.store');
        Route::get('/edit/{id}', [TourController::class, 'edit'])->name('tour.edit');
        Route::put('/update', [TourController::class, 'update'])->name('tour.update');
        Route::get('/delete/{id}', [TourController::class, 'destroy'])->name('tour.destroy');
    });

   //Slider
   Route::prefix('/slider')->group(function () {
    Route::get('/', [SliderController::class, 'index'])->name('slider.index');
    Route::post('/store', [SliderController::class, 'store'])->name('slider.store');
    Route::get('/edit/{id}', [SliderController::class, 'edit'])->name('slider.edit');
    Route::put('/update', [SliderController::class, 'update'])->name('slider.update');
    Route::get('/delete/{id}', [SliderController::class, 'destroy'])->name('slider.destroy');
});
});



require __DIR__ . '/auth.php';
