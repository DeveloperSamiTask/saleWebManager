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
                'roles' => [1],
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
                'roles' => [1],
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
                'roles' => [1, 3, 4],
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
                'roles' => [1],
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
                'roles' => ['Administrador', 'Controller'],
                'icon' => 'mdi mdi-group',
                'text' => 'Modulos',
                'dataI18n' => 'Modules',
                'submenu' => [
                    [
                        'route' => 'modules.index',
                        'text' => 'Modulos',
                        'dataI18n' => 'Modules',
                    ],
                    [
                        'route' => 'kiosks.index',
                        'text' => 'Kioskos',
                        'dataI18n' => 'Kiosks',
                    ],
                    [
                        'route' => 'categories.index',
                        'text' => 'Categorias',
                        'dataI18n' => 'Categories',
                    ],
                    [
                        'route' => 'families.index',
                        'text' => 'Familias',
                        'dataI18n' => 'Families',
                    ],
                    [
                        'route' => 'lines.index',
                        'text' => 'Lineas',
                        'dataI18n' => 'Lines',
                    ],
                    [
                        'route' => 'groups.index',
                        'text' => 'Grupos',
                        'dataI18n' => 'Groups',
                    ],
                    [
                        'route' => 'measures.index',
                        'text' => 'Medidas',
                        'dataI18n' => 'Measures',
                    ],
                ],
            ],
            [
                'roles' => ['Administrador', 'Controller'],
                'icon' => 'mdi mdi-list-box-outline',
                'text' => 'Recetas',
                'dataI18n' => 'Recipes',
                'submenu' => [
                    [
                        'route' => 'recipes.index',
                        'text' => 'Lista',
                        'dataI18n' => 'List',
                    ],
                    [
                        'route' => 'recipes.formula',
                        'text' => 'Formula',
                        'dataI18n' => 'Formula',
                    ],
                    [
                        'route' => 'recipes.sales',
                        'text' => 'Ventas',
                        'dataI18n' => 'Sales',
                    ],
                ],
            ],
            [
                'roles' => ['Administrador'],
                'header' => true,
                'text' => 'Usuarios',
                'dataI18n' => 'Users',
            ],
            [
                'roles' => ['Administrador'],
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
