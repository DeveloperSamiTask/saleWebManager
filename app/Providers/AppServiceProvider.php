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
                'companies' => [1],
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
                'companies' => [1],
                'header' => true,
                'text' => 'Cajas',
                'dataI18n' => 'Boxes',
            ],
            [
                'roles' => [1, 2],
                'companies' => [1],
                'route' => 'Boleteria',
                'icon' => 'mdi mdi-cart-arrow-down',
                'text' => 'Generar Lista Entradas',
                'dataI18n' => 'Generate Coupon',
            ],
            [
                'roles' => [1, 2],
                'companies' => [1],
                'route' => 'Boleteria.fdt',
                'icon' => 'mdi mdi-ghost-off',
                'text' => 'FDT Entradas',
                'dataI18n' => 'Generate Coupon FDT',
            ],
            [
                'roles' => [],
                'companies' => [1],
                'route' => 'validateList',
                'icon' => 'mdi mdi-playlist-check',
                'text' => 'Validar lista entradas',
                'dataI18n' => 'Validate list entries',
            ],
            [
                'roles' => [1, 2],
                'companies' => [2, 3],
                'route' => 'cupon.validate',
                'icon' => 'mdi mdi-newspaper-check',
                'text' => 'Validar Cupón',
                'dataI18n' => 'Validate Coupon',
            ],
            [
                'roles' => [1, 3, 4],
                'companies' => [1],
                'header' => true,
                'text' => 'Cupones',
                'dataI18n' => 'Coupons',
            ],
            [
                'roles' => [1, 3, 4],
                'companies' => [1],
                'route' => 'coupon.index',
                'icon' => 'mdi mdi-ticket-percent-outline',
                'text' => 'Cupones Cumpleaños',
                'dataI18n' => 'Birthday Coupons',
            ],
            [
                'roles' => [1, 2, 3, 4],
                'companies' => [1],
                'header' => true,
                'text' => 'Pago Link',
                'dataI18n' => 'Payment Link',
            ],
            [
                'roles' => [1, 2],
                'companies' => [1],
                'route' => 'paymentLink.validate',
                'icon' => 'mdi mdi-cart-percent',
                'text' => 'V. Entradas Pago Link',
                'dataI18n' => 'Validate Payment Entries Link',
            ],
            [
                'roles' => [1, 2],
                'companies' => [1],
                'route' => 'paymentLink.validate_food',
                'icon' => 'mdi mdi-food',
                'text' => 'V. Comidas Pago Link',
                'dataI18n' => 'Validate paid meals link',
            ],
            [
                'roles' => [1, 3, 4],
                'companies' => [1],
                'icon' => 'mdi mdi-file-chart',
                'text' => 'Lista de Pagos',
                'dataI18n' => 'Payment List',
                'submenu' => [
                    [
                        'route' => 'paymentLink.index',
                        'text' => 'Lista',
                        'dataI18n' => 'List',
                    ],
                    [
                        'route' => 'paymentLink.add',
                        'text' => 'Nuevo Pago',
                        'dataI18n' => 'New Payment',
                    ],
                ],
            ],
            [
                'roles' => [1, 3, 4],
                'companies' => [1],
                'route' => 'paymentLink.promotions',
                'icon' => 'mdi mdi-percent-box-outline',
                'text' => 'Promociones',
                'dataI18n' => 'Promotions',
            ],
            [
                'roles' => [1, 2, 5],
                'companies' => [1],
                'header' => true,
                'text' => 'Pase de Cortesía',
                'dataI18n' => 'Courtesy Pass',
            ],
            [
                'roles' => [1, 2],
                'companies' => [1],
                'route' => 'courtesy.validate',
                'icon' => 'mdi mdi-instagram',
                'text' => 'V. Entradas Pase Cortesia',
                'dataI18n' => 'Validate courtesy pass entries',
            ],
            [
                'roles' => [1],
                'companies' => [1],
                'route' => 'courtesy.validate_food',
                'icon' => 'mdi mdi-food-fork-drink',
                'text' => 'V. Comidas Pase Cortesia',
                'dataI18n' => 'Validate courtesy pass food',
            ],
            [
                'roles' => [1, 5],
                'companies' => [1],
                'icon' => 'mdi mdi-file-chart',
                'text' => 'Influencers',
                'dataI18n' => 'Influencers',
                'submenu' => [
                    [
                        'route' => 'courtesy.index',
                        'text' => 'Lista',
                        'dataI18n' => 'List',
                    ],
                    [
                        'route' => 'courtesy.add',
                        'text' => 'Nuevo',
                        'dataI18n' => 'New',
                    ],
                ],
            ],
            [
                'roles' => [1, 5],
                'companies' => [1],
                'route' => 'courtesy.promotions',
                'icon' => 'mdi mdi-percent-box-outline',
                'text' => 'Promociones',
                'dataI18n' => 'Promotions',
            ],
            [
                'roles' => [1, 2, 3, 4, 5],
                'companies' => [1],
                'header' => true,
                'text' => 'Pase Cortesía FDT',
                'dataI18n' => 'FDT Pass Courtesy',
            ],
            [
                'roles' => [1],
                'companies' => [1],
                'route' => 'fdt.validate',
                'icon' => 'mdi mdi-halloween',
                'text' => 'FDT. Entradas Pase Cortesia',
                'dataI18n' => 'Validate courtesy pass FDT entries',
            ],
            [
                'roles' => [1, 2],
                'companies' => [1],
                'route' => 'fdt.validate_food',
                'icon' => 'mdi mdi-coffin',
                'text' => 'FDT. Comidas Pase Cortesia',
                'dataI18n' => 'Validate courtesy pass FDT food',
            ],
            [
                'roles' => [1, 3, 4,5],
                'companies' => [1],
                'icon' => 'mdi mdi-ghost',
                'text' => 'Pases FDT',
                'dataI18n' => 'FDT Pass',
                'submenu' => [
                    [
                        'route' => 'fdt.index',
                        'text' => 'Lista',
                        'dataI18n' => 'List',
                    ],
                    [
                        'route' => 'fdt.add',
                        'text' => 'Nuevo',
                        'dataI18n' => 'New',
                    ],
                ],
            ],
            [
                'roles' => [1, 3,  4,5],
                'companies' => [1],
                'route' => 'fdt.promotions',
                'icon' => 'mdi mdi-percent-box-outline',
                'text' => 'Promociones',
                'dataI18n' => 'Promotions',
            ],
            [

                'roles' => [1, 3, 4],
                'companies' => [1],
                'header' => true,
                'text' => 'Controllers',
                'dataI18n' => 'Controllers',
            ],
            [
                'roles' => [1, 3, 4],
                'companies' => [1],
                'route' => 'partners',
                'icon' => 'mdi mdi-card-account-details-star-outline',
                'text' => 'Socios',
                'dataI18n' => 'Partners',
            ],
            [
                'roles' => [1, 4],
                'companies' => [1],
                'route' => 'cambioDNI',
                'icon' => 'mdi mdi-card-account-details-outline',
                'text' => 'Rectificación de DNI',
                'dataI18n' => 'ID rectification',
            ],
            [
                'roles' => [1, 3, 4],
                'companies' => [1],
                'route' => 'coupons',
                'icon' => 'mdi mdi-ticket-percent-outline',
                'text' => 'Cupones Internos',
                'dataI18n' => 'Internal coupons',
            ],
            [
                'roles' => [1, 2, 3, 4],
                'companies' => [1],
                'header' => true,
                'text' => 'Reportes',
                'dataI18n' => 'Reports',
            ],
            [
                'roles' => [1, 2, 3, 4],
                'companies' => [1],
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
                'companies' => [1],
                'icon' => 'mdi mdi-file-chart',
                'text' => 'Reporte Pago Link',
                'dataI18n' => 'Paid Link Reports',
                'submenu' => [
                    [
                        'route' => 'reports.payment_link.combos',
                        'text' => 'Validaciones Combos',
                        'dataI18n' => 'Validation combos',
                    ],
                ],
            ],
            [
                'roles' => [1, 3, 4],
                'companies' => [1],
                'route' => 'reportPartners',
                'icon' => 'mdi mdi-format-list-bulleted-type',
                'text' => 'Reporte Socios',
                'dataI18n' => 'Report partners',
            ],
            [
                'roles' => [1],
                'companies' => [1],
                'header' => true,
                'text' => 'Configuraciones',
                'dataI18n' => 'Settings',
            ],
            [
                'roles' => [1],
                'companies' => [1],
                'route' => 'template.index',
                'icon' => 'mdi mdi-file-pdf-box',
                'text' => 'Plantillas',
                'dataI18n' => 'Templates',
            ],
            [
                'roles' => [1],
                'companies' => [1],
                'route' => 'users.index',
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
