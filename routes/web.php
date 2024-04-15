<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dashboard;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\SaleWebs;
use App\Http\Controllers\TableEntries;
use App\Http\Controllers\ValidateWebs;
use App\Http\Controllers\CashierReport;

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

Route::get('/', [LoginController::class, 'index'])->name('login');
Route::post('/initLogin', [LoginController::class, 'login']);
Route::get('/Logout', [LoginController::class, 'logout'])->name('logout');

// Agrupar todas las rutas protegidas por autenticación
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/Inicio', [Dashboard::class, 'index'])->name('Dashboard');
    Route::get('/chartEntries', [Dashboard::class, 'chartEntries']);

    // Rutas para SaleWebs
    Route::get('/Boleteria', [SaleWebs::class, 'index'])->name('Boleteria');
    Route::post('viewTicket', [SaleWebs::class, 'getTicket']);
    Route::post('sendWhatsapp', [SaleWebs::class, 'whatsapp']);
    Route::get('token/{token}', [SaleWebs::class, 'generateQr']);
    Route::post('printQR', [SaleWebs::class, 'print']);

    // Rutas para ValidateWebs
    Route::get('/Validar_Lista', [ValidateWebs::class, 'index'])->name('validateList');
    Route::post('viewList', [ValidateWebs::class, 'getList']);
    Route::post('printTickets', [ValidateWebs::class, 'print']);

    // Rutas para TableEntries
    Route::get('/Lista_Entradas', [TableEntries::class, 'index'])->name('listEntries');
    Route::get('/Tabla_Entradas', [TableEntries::class, 'tableEntries']);


    // Rutas para TableEntries
    Route::get('/Ventas_Web', [CashierReport::class, 'index'])->name('saleWeb');
    Route::get('/Tabla_Cajeras', [CashierReport::class, 'tableCashier']);
    Route::get('/Factura/{id}', [CashierReport::class, 'invoice']);
    Route::post('/Factura/checkInvoice', [CashierReport::class, 'check']);
});
