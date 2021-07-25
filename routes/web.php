<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('/home');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/data-capture', [App\Http\Controllers\DataCaptureController::class, 'index'])->name('data-capture');

Route::get('/data-capture/capture/{termo?}', [App\Http\Controllers\DataCaptureController::class, 'getCarsData'])->name('getCarsData');

Route::get('/car-list', [App\Http\Controllers\CarListController::class, 'index'])->name('car-list');

Route::get('/car-list/deletar/{id?}', [App\Http\Controllers\CarListController::class, 'deleteCar'])->name('deleteCar');
