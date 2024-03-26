<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dashboard;
use App\Http\Controllers\SaleWebs;

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

Route::controller(Dashboard::class)->group(function ($route) {

    Route::get('/', 'index')->name('Dashboard');
});

Route::controller(SaleWebs::class)->group(function ($route) {

    Route::get('/Boleteria', 'index')->name('Boleteria');
    Route::post('/viewTicket', 'getTicket');
    Route::post('/sendWhatsapp', 'whatsapp');
    Route::get('/token/{token}', 'generateQr');
    Route::post('/printQR', 'print');
});
