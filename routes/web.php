<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dashboard;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\SaleWebs;
use App\Http\Controllers\TableEntries;
use App\Http\Controllers\ValidateWebs;
use App\Http\Controllers\CashierReport;
use App\Http\Controllers\CouponManagementController;
use App\Http\Controllers\CouponsController;
use App\Http\Controllers\DniController;
use App\Http\Controllers\Notify;
use App\Http\Controllers\PartnerController;
use App\Http\Controllers\TemplateController;

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
Route::post('/Logout', [LoginController::class, 'logout'])->name('logout');

// Agrupar todas las rutas protegidas por autenticación
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/Inicio', [Dashboard::class, 'index'])->name('Dashboard');
    Route::get('/chartEntries', [Dashboard::class, 'chartEntries']);
    Route::post('/selectBox', [Dashboard::class, 'sessionBox']);

    // Rutas para SaleWebs
    Route::get('/Boleteria', [SaleWebs::class, 'index'])->name('Boleteria');
    Route::post('viewTicket', [SaleWebs::class, 'getTicket']);
    Route::post('sendWhatsapp', [SaleWebs::class, 'whatsapp']);
    Route::get('token/{token}', [SaleWebs::class, 'generateQr']);
    Route::post('printQR', [SaleWebs::class, 'print']);
    Route::get('ticketsValidate', [SaleWebs::class, 'tickets']);

    // Rutas para ValidateWebs
    Route::get('/Validar_Lista', [ValidateWebs::class, 'index'])->name('validateList');
    Route::post('viewList', [ValidateWebs::class, 'getList']);
    Route::post('printTickets', [ValidateWebs::class, 'print']);
    Route::get('/boxes', [ValidateWebs::class, 'viewBoxes']);

    // Rutas para TableEntries
    Route::get('/Lista_Entradas', [TableEntries::class, 'index'])->name('listEntries');
    Route::get('/Tabla_Entradas', [TableEntries::class, 'tableEntries']);
    Route::get('/Tabla_Dashboard', [TableEntries::class, 'dashboardEntries']);


    // Rutas para TableEntries
    Route::get('/Ventas_Web', [CashierReport::class, 'index'])->name('saleWeb');
    Route::get('/Tabla_Cajeras', [CashierReport::class, 'tableCashier']);
    Route::get('/Factura/{id}', [CashierReport::class, 'invoice']);
    Route::post('/Factura/checkInvoice', [CashierReport::class, 'check']);


    Route::get('/Rectificacion_DNI', [DniController::class, 'index'])->name('cambioDNI');
    Route::get('/Tabla_Logs', [DniController::class, 'logsTable']);
    Route::post('/requestChangeDni', [DniController::class, 'changeDNI']);
    Route::post('/updatedDocument', [DniController::class, 'update']);


    Route::get('/Result_Notify', [Notify::class, 'index']);
    Route::post('/Modify_View_Notification', [Notify::class, 'modify_view']);

    Route::get('/Cupones_Internos', [CouponsController::class, 'index'])->name('coupons');
    Route::get('/coupons_table', [CouponsController::class, 'logsTable']);
    Route::post('/insertCoupon', [CouponsController::class, 'insert']);
    Route::post('/uploadCoupon', [CouponsController::class, 'upload']);

    Route::get('/Socios', [PartnerController::class, 'index'])->name('partners');
    Route::get('/partners_table', [PartnerController::class, 'show']);
    Route::post('/insertPartner', [PartnerController::class, 'insert']);
    Route::post('/searchPartner', [PartnerController::class, 'search']);
    Route::post('/renewPartner', [PartnerController::class, 'renew']);
    Route::post('/editPartner', [PartnerController::class, 'update']);
    Route::get('/Socios_Validaciones', [PartnerController::class, 'report_view'])->name('reportPartners');

    Route::prefix('Cupon')->group(function () {
        Route::get('/', [CouponManagementController::class, 'index'])->name('coupon.index'); // Mostrar lista de cupones
        Route::post('/DNI', [CouponManagementController::class, 'searchDNI'])->name('cupon.dni'); // Formulario de creación
        Route::get('/Show', [CouponManagementController::class, 'show'])->name('cupon.show');
        Route::post('/Promotions', [CouponManagementController::class, 'getPromotions'])->name('cupon.promotions'); // Formulario de creación
        Route::post('/Create', [CouponManagementController::class, 'store'])->name('cupon.store'); // Formulario de creación
        Route::get('/pdf/{code}', [CouponManagementController::class, 'generatePdf'])->name('cupon.pdf'); // Formulario para validar
        Route::post('/Status', [CouponManagementController::class, 'changeStatus'])->name('cupon.status'); // Validar cupón

        Route::get('/Validar_Cupon', [CouponManagementController::class, 'viewValidate'])->name('cupon.validate'); // Formulario para validar
        Route::get('/Search/{code}', [CouponManagementController::class, 'search'])->name('cupon.search'); // Formulario para validar
    });

    Route::prefix('Plantillas')->group(function () {
        Route::get('/', [TemplateController::class, 'index'])->name('template.index'); // Vista lista de plantillas
        Route::get('/Show', [TemplateController::class, 'show'])->name('template.show'); // Api plantillas
        Route::post('/Store', [TemplateController::class, 'store'])->name('template.store'); // Guardar plantilla
        Route::post('/Update', [TemplateController::class, 'update'])->name('template.update'); // Actualizar plantilla
    });
});

Route::get('/tableValidate', [PartnerController::class, 'showValidate']);
