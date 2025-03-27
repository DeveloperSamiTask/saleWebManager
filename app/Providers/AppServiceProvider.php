<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $menus = [
            [
                'roles' => [1, 2, 3, 4],
                'icon' => 'mdi mdi-home-outline',
                'text' => 'Dashboard',
                'dataI18n' => 'Dashboard',
                'submenu' => [
                    [
                        'route' => 'Dashboard',
                        'text' => 'Ventas Web',
                        'dataI18n' => 'sale web',
                    ],

                ],
            ],
            [
                'roles' => [1, 2],
                'header' => true,
                'text' => 'Cajas',
                'dataI18n' => 'Boxes',
            ],
            [
                'roles' => [1, 2],
                'route' => 'Boleteria',
                'icon' => 'mdi mdi-cart-arrow-down',
                'text' => 'Generar Lista Entradas',
                'dataI18n' => 'Generate Coupon',
            ],
            [
                'roles' => [1, 2],
                'route' => 'validateList',
                'icon' => 'mdi mdi-playlist-check',
                'text' => 'Validate Coupon',
                'dataI18n' => 'Valir Lista Entradas',
            ],
            [
                'roles' => [1, 2, 3, 4],
                'header' => true,
                'text' => 'Cupones',
                'dataI18n' => 'Coupons',
            ],
            [
                'roles' => [1, 3, 4],
                'route' => 'coupon.index',
                'icon' => 'mdi mdi-ticket-percent-outline',
                'text' => 'Cupones Cumpleaños',
                'dataI18n' => 'Birthday Coupons',
            ],
            [
                'roles' => [1, 3, 4],
                'header' => true,
                'text' => 'Controllers',
                'dataI18n' => 'Controllers',
            ],
            [
                'roles' => [1, 3, 4],
                'route' => 'partners',
                'icon' => 'mdi mdi-card-account-details-star-outline',
                'text' => 'Socios',
                'dataI18n' => 'Partners',
            ],
            [
                'roles' => [1, 4],
                'route' => 'cambioDNI',
                'icon' => 'mdi mdi-card-account-details-outline',
                'text' => 'Rectificación de DNI',
                'dataI18n' => 'ID rectification',
            ],
            [
                'roles' => [1, 3, 4],
                'route' => 'coupons',
                'icon' => 'mdi mdi-ticket-percent-outline',
                'text' => 'Cupones Internos',
                'dataI18n' => 'Internal coupons',
            ],
            [
                'roles' => [1, 2, 3, 4],
                'header' => true,
                'text' => 'Reportes',
                'dataI18n' => 'Reports',
            ],

            [
                'roles' => [1, 2, 3, 4],
                'icon' => 'mdi mdi-file-chart',
                'text' => 'Reporte Entradas',
                'dataI18n' => 'Input Reports',
                'submenu' => [
                    [
                        'route' => 'saleWeb',
                        'text' => 'Ventas Web',
                        'dataI18n' => 'sale web',
                    ],
                    [
                        'route' => 'listEntries',
                        'text' => 'Detalle Entrada',
                        'dataI18n' => 'detail entries',
                    ],
                ],
            ],
            [
                'roles' => [1, 3, 4],
                'route' => 'reportPartners',
                'icon' => 'mdi mdi-format-list-bulleted-type',
                'text' => 'Reporte Socios',
                'dataI18n' => 'Report partners',
            ],
            [
                'roles' => [1],
                'header' => true,
                'text' => 'Configuraciones',
                'dataI18n' => 'Settings',
            ],
            [
                'roles' => [1],
                'route' => 'template.index',
                'icon' => 'mdi mdi-file-pdf-box',
                'text' => 'Plantillas',
                'dataI18n' => 'Templates',
            ],
            [
                'roles' => [1],
                'route' => 'saleWeb',
                'icon' => 'mdi mdi-account-multiple',
                'text' => 'Usuarios',
                'dataI18n' => 'Users',
            ],
        ];

        View::share('menus', $menus);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
