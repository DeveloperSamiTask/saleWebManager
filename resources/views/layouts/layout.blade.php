<!DOCTYPE html>

<html lang="es" class="light-style layout-navbar-fixed layout-menu-fixed layout-compact " dir="ltr"
    data-theme="theme-default" data-assets-path="{{ asset('') }}" data-template="vertical-menu-template">

<head>
    <meta charset="utf-8">
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">

    <title>Sistema de Validacion</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="title" content="La Granja Villa (Valida tus entradas webs)">

    <meta name="description"
        content="¡Bienvenido al Parque Recreativo Tropical! Sumérgete en la diversión con una variedad de juegos y atracciones emocionantes para toda la familia. Disfruta de la belleza y la tranquilidad de nuestra exhibición de peces tropicales, donde podrás admirar una amplia variedad de especies exóticas. Además, acércate a nuestros amigables caballos y experimenta la alegría de acariciar y alimentar a estos majestuosos animales. En el Parque Recreativo Tropical, la aventura y la naturaleza se unen para crear recuerdos inolvidables para ti y tus seres queridos.">
    <meta property="og:image" content="{{ asset('img/logo.png') }}">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('img/logo.png') }}">


    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="">
    <link href="{{ asset('css/css2?family=Inter:wght@300;400;500;600;700&ampdisplay=swap') }}" rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet" href="{{ asset('vendor/fonts/materialdesignicons.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/fonts/flag-icons.css') }}">

    <!-- Menu waves for no-customizer fix -->
    <link rel="stylesheet" href="{{ asset('vendor/libs/node-waves/node-waves.css') }}">

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('vendor/css/rtl/core.css') }}" class="template-customizer-core-css">
    <link rel="stylesheet" href="{{ asset('vendor/css/rtl/theme-default.css') }}" class="template-customizer-theme-css">
    <link rel="stylesheet" href="{{ asset('css/demo.css') }}">

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ asset('vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/libs/typeahead-js/typeahead.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/libs/bootstrap-select/bootstrap-select.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/libs/select2/select2.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/libs/spinkit/spinkit.css') }}">

    <link rel="stylesheet" href="{{ asset('vendor/libs/%40form-validation/umd/styles/index.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/libs/bootstrap-datepicker/bootstrap-datepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/libs/jquery-timepicker/jquery-timepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/libs/sweetalert2/sweetalert2.css') }}">


    @yield('styles')

    <!-- Page CSS -->


    <!-- Helpers -->
    <script src="{{ asset('vendor/js/helpers.js') }}"></script>
    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <script src="{{ asset('vendor/js/template-customizer.js') }}"></script>
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="{{ asset('js/config.js') }}"></script>

</head>

<body>
    @php
        function obtenerNombreRol($idRol)
        {
            $roles = [
                1 => 'ADMIN',
                2 => 'CAJA',
                3 => 'COLEGIOS',
            ];

            if (array_key_exists($idRol, $roles)) {
                return $roles[$idRol];
            } else {
                return 'Rol Desconocido';
            }
        }
    @endphp
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar  ">
        <div class="layout-container">
            <!-- Menu -->
            <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
                <div class="app-brand demo ">
                    <a href="index.html" class="app-brand-link">
                        <span class="app-brand-logo demo">
                            <span style="color:var(--bs-primary);">
                                <svg width="268" height="150" viewbox="0 0 38 20" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M30.0944 2.22569C29.0511 0.444187 26.7508 -0.172113 24.9566 0.849138C23.1623 1.87039 22.5536 4.14247 23.5969 5.92397L30.5368 17.7743C31.5801 19.5558 33.8804 20.1721 35.6746 19.1509C37.4689 18.1296 38.0776 15.8575 37.0343 14.076L30.0944 2.22569Z"
                                        fill="currentColor"></path>
                                    <path
                                        d="M30.171 2.22569C29.1277 0.444187 26.8274 -0.172113 25.0332 0.849138C23.2389 1.87039 22.6302 4.14247 23.6735 5.92397L30.6134 17.7743C31.6567 19.5558 33.957 20.1721 35.7512 19.1509C37.5455 18.1296 38.1542 15.8575 37.1109 14.076L30.171 2.22569Z"
                                        fill="url(#paint0_linear_2989_100980)" fill-opacity="0.4"></path>
                                    <path
                                        d="M22.9676 2.22569C24.0109 0.444187 26.3112 -0.172113 28.1054 0.849138C29.8996 1.87039 30.5084 4.14247 29.4651 5.92397L22.5251 17.7743C21.4818 19.5558 19.1816 20.1721 17.3873 19.1509C15.5931 18.1296 14.9843 15.8575 16.0276 14.076L22.9676 2.22569Z"
                                        fill="currentColor"></path>
                                    <path
                                        d="M14.9558 2.22569C13.9125 0.444187 11.6122 -0.172113 9.818 0.849138C8.02377 1.87039 7.41502 4.14247 8.45833 5.92397L15.3983 17.7743C16.4416 19.5558 18.7418 20.1721 20.5361 19.1509C22.3303 18.1296 22.9391 15.8575 21.8958 14.076L14.9558 2.22569Z"
                                        fill="currentColor"></path>
                                    <path
                                        d="M14.9558 2.22569C13.9125 0.444187 11.6122 -0.172113 9.818 0.849138C8.02377 1.87039 7.41502 4.14247 8.45833 5.92397L15.3983 17.7743C16.4416 19.5558 18.7418 20.1721 20.5361 19.1509C22.3303 18.1296 22.9391 15.8575 21.8958 14.076L14.9558 2.22569Z"
                                        fill="url(#paint1_linear_2989_100980)" fill-opacity="0.4"></path>
                                    <path
                                        d="M7.82901 2.22569C8.87231 0.444187 11.1726 -0.172113 12.9668 0.849138C14.7611 1.87039 15.3698 4.14247 14.3265 5.92397L7.38656 17.7743C6.34325 19.5558 4.04298 20.1721 2.24875 19.1509C0.454514 18.1296 -0.154233 15.8575 0.88907 14.076L7.82901 2.22569Z"
                                        fill="currentColor"></path>
                                    <defs>
                                        <lineargradient id="paint0_linear_2989_100980" x1="5.36642" y1="0.849138"
                                            x2="10.532" y2="24.104" gradientunits="userSpaceOnUse">
                                            <stop offset="0" stop-opacity="1"></stop>
                                            <stop offset="1" stop-opacity="0"></stop>
                                        </lineargradient>
                                        <lineargradient id="paint1_linear_2989_100980" x1="5.19475" y1="0.849139"
                                            x2="10.3357" y2="24.1155" gradientunits="userSpaceOnUse">
                                            <stop offset="0" stop-opacity="1"></stop>
                                            <stop offset="1" stop-opacity="0"></stop>
                                        </lineargradient>
                                    </defs>
                                </svg>
                            </span>
                        </span>
                        <span class="app-brand-text demo menu-text fw-bold ms-2">Materialize</span>
                    </a>

                    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
                        <svg width="22" height="22" viewbox="0 0 22 22" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M11.4854 4.88844C11.0081 4.41121 10.2344 4.41121 9.75715 4.88844L4.51028 10.1353C4.03297 10.6126 4.03297 11.3865 4.51028 11.8638L9.75715 17.1107C10.2344 17.5879 11.0081 17.5879 11.4854 17.1107C11.9626 16.6334 11.9626 15.8597 11.4854 15.3824L7.96672 11.8638C7.48942 11.3865 7.48942 10.6126 7.96672 10.1353L11.4854 6.61667C11.9626 6.13943 11.9626 5.36568 11.4854 4.88844Z"
                                fill="currentColor" fill-opacity="0.6"></path>
                            <path
                                d="M15.8683 4.88844L10.6214 10.1353C10.1441 10.6126 10.1441 11.3865 10.6214 11.8638L15.8683 17.1107C16.3455 17.5879 17.1192 17.5879 17.5965 17.1107C18.0737 16.6334 18.0737 15.8597 17.5965 15.3824L14.0778 11.8638C13.6005 11.3865 13.6005 10.6126 14.0778 10.1353L17.5965 6.61667C18.0737 6.13943 18.0737 5.36568 17.5965 4.88844C17.1192 4.41121 16.3455 4.41121 15.8683 4.88844Z"
                                fill="currentColor" fill-opacity="0.38"></path>
                        </svg>
                    </a>
                </div>
                <div class="menu-inner-shadow"></div>
                <ul class="menu-inner py-1">
                    <li class="menu-item">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <i class="menu-icon tf-icons mdi mdi-home-outline"></i>
                            <div data-i18n="Dashboards">Dashboards</div>
                            <div class="badge bg-primary rounded-pill ms-auto">1</div>
                        </a>
                        <ul class="menu-sub">
                            <li class="menu-item">
                                <a href="{{ route('Dashboard') }}" class="menu-link">
                                    <div data-i18n="sale web">Ventas Web</div>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- Apps & Pages -->
                    <li class="menu-header fw-medium mt-4">
                        <span class="menu-header-text" data-i18n="Apps & Pages">Apps &amp; Paginas</span>
                    </li>

                    <li class="menu-item">
                        <a href="{{ route('Boleteria') }}" class="menu-link">
                            <i class="menu-icon tf-icons mdi mdi-cart-arrow-down"></i>
                            <div data-i18n="Generate Coupon">Generar Lista Entradas</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ route('validateList') }}" class="menu-link">
                            <i class="menu-icon tf-icons mdi mdi-playlist-check"></i>
                            <div data-i18n="Validate Coupon">Valir Lista Entradas</div>
                        </a>
                    </li>

                    <!-- Apps & Pages -->
                    <li class="menu-header fw-medium mt-4">
                        <span class="menu-header-text" data-i18n="Reports">Reports</span>
                    </li>

                    <li class="menu-item">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <i class="menu-icon tf-icons mdi mdi-file-chart"></i>
                            <div>Reportes Entradas</div>
                        </a>
                        <ul class="menu-sub">
                            <li class="menu-item">
                                <a href="{{ route('saleWeb') }}" class="menu-link">
                                    <div data-i18n="sale web">Ventas Web</div>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="{{ route('listEntries') }}" class="menu-link">
                                    <div data-i18n="detail entries">Detalle Entrada</div>
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </aside>
            <!-- / Menu -->
            <!-- Layout container -->
            <div class="layout-page">
                <!-- Navbar -->
                <nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
                    id="layout-navbar">
                    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0   d-xl-none ">
                        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                            <i class="mdi mdi-menu mdi-24px"></i>
                        </a>
                    </div>
                    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
                        <!-- Search -->
                        <div class="navbar-nav align-items-center">
                            <div class="nav-item navbar-search-wrapper mb-0">
                                <a class="nav-item nav-link search-toggler fw-normal px-0" href="javascript:void(0);">
                                    <i class="mdi mdi-magnify mdi-24px scaleX-n1-rtl"></i>
                                    <span class="d-none d-md-inline-block text-muted">Buscar (Ctrl +a lt + K)</span>
                                </a>
                            </div>
                        </div>
                        <!-- /Search -->
                        <ul class="navbar-nav flex-row align-items-center ms-auto">

                            <!-- Style Switcher -->
                            <li class="nav-item dropdown-style-switcher dropdown me-1 me-xl-0">
                                <a class="nav-link btn btn-text-secondary rounded-pill btn-icon dropdown-toggle hide-arrow"
                                    href="javascript:void(0);" data-bs-toggle="dropdown">
                                    <i class='mdi mdi-24px'></i>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end dropdown-styles">
                                    <li>
                                        <a class="dropdown-item" href="javascript:void(0);" data-theme="light">
                                            <span class="align-middle"><i
                                                    class='mdi mdi-weather-sunny me-2'></i>Light</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="javascript:void(0);" data-theme="dark">
                                            <span class="align-middle"><i
                                                    class="mdi mdi-weather-night me-2"></i>Dark</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="javascript:void(0);" data-theme="system">
                                            <span class="align-middle"><i
                                                    class="mdi mdi-monitor me-2"></i>System</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <!-- / Style Switcher-->

                            <!-- Quick links  -->
                            <li class="nav-item dropdown-shortcuts navbar-dropdown dropdown me-1 me-xl-0">
                                <a class="nav-link btn btn-text-secondary rounded-pill btn-icon dropdown-toggle hide-arrow"
                                    href="javascript:void(0);" data-bs-toggle="dropdown" data-bs-auto-close="outside"
                                    aria-expanded="false">
                                    <i class='mdi mdi-view-grid-plus-outline mdi-24px'></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end py-0">
                                    <div class="dropdown-menu-header border-bottom">
                                        <div class="dropdown-header d-flex align-items-center py-3">
                                            <h5 class="text-body mb-0 me-auto">Shortcuts</h5>
                                            <a href="javascript:void(0)" class="dropdown-shortcuts-add text-muted"
                                                data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="Add shortcuts"><i
                                                    class="mdi mdi-view-grid-plus-outline mdi-24px"></i></a>
                                        </div>
                                    </div>
                                    <div class="dropdown-shortcuts-list scrollable-container">
                                        <div class="row row-bordered overflow-visible g-0">
                                            <div class="dropdown-shortcuts-item col">
                                                <span
                                                    class="dropdown-shortcuts-icon bg-label-secondary rounded-circle mb-2">
                                                    <i class="mdi mdi-calendar fs-4"></i>
                                                </span>
                                                <a href="app-calendar.html" class="stretched-link">Calendar</a>
                                                <small class="text-muted mb-0">Appointments</small>
                                            </div>
                                            <div class="dropdown-shortcuts-item col">
                                                <span
                                                    class="dropdown-shortcuts-icon bg-label-secondary rounded-circle mb-2">
                                                    <i class="mdi mdi-file-document-outline fs-4"></i>
                                                </span>
                                                <a href="app-invoice-list.html" class="stretched-link">Invoice
                                                    App</a>
                                                <small class="text-muted mb-0">Manage Accounts</small>
                                            </div>
                                        </div>
                                        <div class="row row-bordered overflow-visible g-0">
                                            <div class="dropdown-shortcuts-item col">
                                                <span
                                                    class="dropdown-shortcuts-icon bg-label-secondary rounded-circle mb-2">
                                                    <i class="mdi mdi-account-outline fs-4"></i>
                                                </span>
                                                <a href="app-user-list.html" class="stretched-link">User App</a>
                                                <small class="text-muted mb-0">Manage Users</small>
                                            </div>
                                            <div class="dropdown-shortcuts-item col">
                                                <span
                                                    class="dropdown-shortcuts-icon bg-label-secondary rounded-circle mb-2">
                                                    <i class="mdi mdi-shield-check-outline fs-4"></i>
                                                </span>
                                                <a href="app-access-roles.html" class="stretched-link">Role
                                                    Management</a>
                                                <small class="text-muted mb-0">Permission</small>
                                            </div>
                                        </div>
                                        <div class="row row-bordered overflow-visible g-0">
                                            <div class="dropdown-shortcuts-item col">
                                                <span
                                                    class="dropdown-shortcuts-icon bg-label-secondary rounded-circle mb-2">
                                                    <i class="mdi mdi-chart-pie-outline fs-4"></i>
                                                </span>
                                                <a href="index.html" class="stretched-link">Dashboard</a>
                                                <small class="text-muted mb-0">Analytics</small>
                                            </div>
                                            <div class="dropdown-shortcuts-item col">
                                                <span
                                                    class="dropdown-shortcuts-icon bg-label-secondary rounded-circle mb-2">
                                                    <i class="mdi mdi-cog-outline fs-4"></i>
                                                </span>
                                                <a href="pages-account-settings-account.html"
                                                    class="stretched-link">Setting</a>
                                                <small class="text-muted mb-0">Account Settings</small>
                                            </div>
                                        </div>
                                        <div class="row row-bordered overflow-visible g-0">
                                            <div class="dropdown-shortcuts-item col">
                                                <span
                                                    class="dropdown-shortcuts-icon bg-label-secondary rounded-circle mb-2">
                                                    <i class="mdi mdi-help-circle-outline fs-4"></i>
                                                </span>
                                                <a href="pages-faq.html" class="stretched-link">FAQs</a>
                                                <small class="text-muted mb-0">FAQs & Articles</small>
                                            </div>
                                            <div class="dropdown-shortcuts-item col">
                                                <span
                                                    class="dropdown-shortcuts-icon bg-label-secondary rounded-circle mb-2">
                                                    <i class="mdi mdi-dock-window fs-4"></i>
                                                </span>
                                                <a href="modal-examples.html" class="stretched-link">Modals</a>
                                                <small class="text-muted mb-0">Useful Popups</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <!-- Quick links -->

                            <!-- Notification
                            <li class="nav-item dropdown-notifications navbar-dropdown dropdown me-2 me-xl-1">
                                <a class="nav-link btn btn-text-secondary rounded-pill btn-icon dropdown-toggle hide-arrow"
                                    href="javascript:void(0);" data-bs-toggle="dropdown" data-bs-auto-close="outside"
                                    aria-expanded="false">
                                    <i class="mdi mdi-bell-outline mdi-24px"></i>
                                    <span
                                        class="position-absolute top-0 start-50 translate-middle-y badge badge-dot bg-danger mt-2 border"></span>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end py-0">
                                    <li class="dropdown-menu-header border-bottom">
                                        <div class="dropdown-header d-flex align-items-center py-3">
                                            <h6 class="mb-0 me-auto">Notification</h6>
                                            <span class="badge rounded-pill bg-label-primary">8 New</span>
                                        </div>
                                    </li>
                                    <li class="dropdown-notifications-list scrollable-container">
                                        <ul class="list-group list-group-flush">
                                            <li
                                                class="list-group-item list-group-item-action dropdown-notifications-item">
                                                <div class="d-flex gap-2">
                                                    <div class="flex-shrink-0">
                                                        <div class="avatar me-1">
                                                            <img src="{{ asset('img/avatars/1.png') }}"
                                                                alt="" class="w-px-40 h-auto rounded-circle">
                                                        </div>
                                                    </div>
                                                    <div
                                                        class="d-flex flex-column flex-grow-1 overflow-hidden w-px-200">
                                                        <h6 class="mb-1 text-truncate">Congratulation Lettie 🎉</h6>
                                                        <small class="text-truncate text-body">Won the monthly best
                                                            seller gold badge</small>
                                                    </div>
                                                    <div class="flex-shrink-0 dropdown-notifications-actions">
                                                        <small class="text-muted">1h ago</small>
                                                    </div>
                                                </div>
                                            </li>
                                            <li
                                                class="list-group-item list-group-item-action dropdown-notifications-item">
                                                <div class="d-flex gap-2">
                                                    <div class="flex-shrink-0">
                                                        <div class="avatar me-1">
                                                            <span
                                                                class="avatar-initial rounded-circle bg-label-danger">CF</span>
                                                        </div>
                                                    </div>
                                                    <div
                                                        class="d-flex flex-column flex-grow-1 overflow-hidden w-px-200">
                                                        <h6 class="mb-1 text-truncate">Charles Franklin</h6>
                                                        <small class="text-truncate text-body">Accepted your
                                                            connection</small>
                                                    </div>
                                                    <div class="flex-shrink-0 dropdown-notifications-actions">
                                                        <small class="text-muted">12hr ago</small>
                                                    </div>
                                                </div>
                                            </li>
                                            <li
                                                class="list-group-item list-group-item-action dropdown-notifications-item marked-as-read">
                                                <div class="d-flex gap-2">
                                                    <div class="flex-shrink-0">
                                                        <div class="avatar me-1">
                                                            <img src="{{ asset('img/avatars/2.png') }}"
                                                                alt="" class="w-px-40 h-auto rounded-circle">
                                                        </div>
                                                    </div>
                                                    <div
                                                        class="d-flex flex-column flex-grow-1 overflow-hidden w-px-200">
                                                        <h6 class="mb-1 text-truncate">New Message ✉️</h6>
                                                        <small class="text-truncate text-body">You have new message
                                                            from Natalie</small>
                                                    </div>
                                                    <div class="flex-shrink-0 dropdown-notifications-actions">
                                                        <small class="text-muted">1h ago</small>
                                                    </div>
                                                </div>
                                            </li>
                                            <li
                                                class="list-group-item list-group-item-action dropdown-notifications-item">
                                                <div class="d-flex gap-2">
                                                    <div class="flex-shrink-0">
                                                        <div class="avatar me-1">
                                                            <span
                                                                class="avatar-initial rounded-circle bg-label-success"><i
                                                                    class="mdi mdi-cart-outline"></i></span>
                                                        </div>
                                                    </div>
                                                    <div
                                                        class="d-flex flex-column flex-grow-1 overflow-hidden w-px-200">
                                                        <h6 class="mb-1 text-truncate">Whoo! You have new order 🛒
                                                        </h6>
                                                        <small class="text-truncate text-body">ACME Inc. made new
                                                            order $1,154</small>
                                                    </div>
                                                    <div class="flex-shrink-0 dropdown-notifications-actions">
                                                        <small class="text-muted">1 day ago</small>
                                                    </div>
                                                </div>
                                            </li>
                                            <li
                                                class="list-group-item list-group-item-action dropdown-notifications-item marked-as-read">
                                                <div class="d-flex gap-2">
                                                    <div class="flex-shrink-0">
                                                        <div class="avatar me-1">
                                                            <img src="{{ asset('img/avatars/9.png') }}"
                                                                alt="" class="w-px-40 h-auto rounded-circle">
                                                        </div>
                                                    </div>
                                                    <div
                                                        class="d-flex flex-column flex-grow-1 overflow-hidden w-px-200">
                                                        <h6 class="mb-1 text-truncate">Application has been approved
                                                            🚀 </h6>
                                                        <small class="text-truncate text-body">Your ABC project
                                                            application has been approved.</small>
                                                    </div>
                                                    <div class="flex-shrink-0 dropdown-notifications-actions">
                                                        <small class="text-muted">2 days ago</small>
                                                    </div>
                                                </div>
                                            </li>
                                            <li
                                                class="list-group-item list-group-item-action dropdown-notifications-item marked-as-read">
                                                <div class="d-flex gap-2">
                                                    <div class="flex-shrink-0">
                                                        <div class="avatar me-1">
                                                            <span
                                                                class="avatar-initial rounded-circle bg-label-success"><i
                                                                    class="mdi mdi-chart-pie-outline"></i></span>
                                                        </div>
                                                    </div>
                                                    <div
                                                        class="d-flex flex-column flex-grow-1 overflow-hidden w-px-200">
                                                        <h6 class="mb-1 text-truncate">Monthly report is generated
                                                        </h6>
                                                        <small class="text-truncate text-body">July monthly financial
                                                            report is generated </small>
                                                    </div>
                                                    <div class="flex-shrink-0 dropdown-notifications-actions">
                                                        <small class="text-muted">3 days ago</small>
                                                    </div>
                                                </div>
                                            </li>
                                            <li
                                                class="list-group-item list-group-item-action dropdown-notifications-item marked-as-read">
                                                <div class="d-flex gap-2">
                                                    <div class="flex-shrink-0">
                                                        <div class="avatar me-1">
                                                            <img src="{{ asset('img/avatars/5.png') }}"
                                                                alt="" class="w-px-40 h-auto rounded-circle">
                                                        </div>
                                                    </div>
                                                    <div
                                                        class="d-flex flex-column flex-grow-1 overflow-hidden w-px-200">
                                                        <h6 class="mb-1 text-truncate">Send connection request</h6>
                                                        <small class="text-truncate text-body">Peter sent you
                                                            connection request</small>
                                                    </div>
                                                    <div class="flex-shrink-0 dropdown-notifications-actions">
                                                        <small class="text-muted">4 days ago</small>
                                                    </div>
                                                </div>
                                            </li>
                                            <li
                                                class="list-group-item list-group-item-action dropdown-notifications-item">
                                                <div class="d-flex gap-2">
                                                    <div class="flex-shrink-0">
                                                        <div class="avatar me-1">
                                                            <img src="{{ asset('img/avatars/6.png') }}"
                                                                alt="" class="w-px-40 h-auto rounded-circle">
                                                        </div>
                                                    </div>
                                                    <div
                                                        class="d-flex flex-column flex-grow-1 overflow-hidden w-px-200">
                                                        <h6 class="mb-1 text-truncate">New message from Jane</h6>
                                                        <small class="text-truncate text-body">Your have new message
                                                            from Jane</small>
                                                    </div>
                                                    <div class="flex-shrink-0 dropdown-notifications-actions">
                                                        <small class="text-muted">5 days ago</small>
                                                    </div>
                                                </div>
                                            </li>
                                            <li
                                                class="list-group-item list-group-item-action dropdown-notifications-item marked-as-read">
                                                <div class="d-flex gap-2">
                                                    <div class="flex-shrink-0">
                                                        <div class="avatar me-1">
                                                            <span
                                                                class="avatar-initial rounded-circle bg-label-warning"><i
                                                                    class="mdi mdi-alert-circle-outline"></i></span>
                                                        </div>
                                                    </div>
                                                    <div
                                                        class="d-flex flex-column flex-grow-1 overflow-hidden w-px-200">
                                                        <h6 class="mb-1">CPU is running high</h6>
                                                        <small class="text-truncate text-body">CPU Utilization Percent
                                                            is currently at 88.63%,</small>
                                                    </div>
                                                    <div class="flex-shrink-0 dropdown-notifications-actions">
                                                        <small class="text-muted">5 days ago</small>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </li>
                                    <li class="dropdown-menu-footer border-top p-2">
                                        <a href="javascript:void(0);"
                                            class="btn btn-primary d-flex justify-content-center">
                                            View all notifications
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <!--/ Notification -->

                            <!-- User -->
                            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);"
                                    data-bs-toggle="dropdown">
                                    <div class="avatar avatar-online">
                                        <img src="{{ asset('img/avatars/1.png') }}" alt=""
                                            class="w-px-40 h-auto rounded-circle">
                                    </div>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="pages-account-settings-account.html">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 me-3">
                                                    <div class="avatar avatar-online">
                                                        <img src="{{ asset('img/avatars/1.png') }}" alt=""
                                                            class="w-px-40 h-auto rounded-circle">
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <span
                                                        class="fw-medium d-block">{{ session('user')['usuario'] }}</span>
                                                    <small
                                                        class="text-muted">{{ obtenerNombreRol(session('user')['idrol']) }}</small>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <div class="dropdown-divider"></div>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="pages-profile-user.html">
                                            <i class="mdi mdi-account-outline me-2"></i>
                                            <span class="align-middle">My Profile</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="pages-account-settings-account.html">
                                            <i class="mdi mdi-cog-outline me-2"></i>
                                            <span class="align-middle">Settings</span>
                                        </a>
                                    </li>

                                    <li>
                                        <div class="dropdown-divider"></div>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('logout') }}">
                                            <i class="mdi mdi-logout me-2"></i>
                                            <span class="align-middle">Cerrar Sessión</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <!--/ User -->
                        </ul>
                    </div>
                    <!-- Search Small Screens -->
                    <div class="navbar-search-wrapper search-input-wrapper  d-none">
                        <input type="text" class="form-control search-input container-xxl border-0"
                            placeholder="Search..." aria-label="Search...">
                        <i class="mdi mdi-close search-toggler cursor-pointer"></i>
                    </div>
                </nav>
                <!-- / Navbar -->
                <!-- Content wrapper -->
                <div class="content-wrapper">
                    <!-- Content -->
                    @yield('content')
                    <!-- / Content -->
                    <!-- Footer -->
                    <footer class="content-footer footer bg-footer-theme">
                        <div class="container-xxl">
                            <div
                                class="footer-container d-flex align-items-center justify-content-between py-3 flex-md-row flex-column">
                                <div class="mb-2 mb-md-0">
                                    ©
                                    <i class="tf-icons mdi mdi-heart"></i></span>
                                </div>
                                <div class="d-none d-lg-inline-block">
                                </div>
                            </div>
                        </div>
                    </footer>
                    <!-- / Footer -->
                    <div class="content-backdrop fade"></div>
                </div>
                <!-- Content wrapper -->
            </div>
            <!-- / Layout page -->
        </div>
        <!-- Overlay -->
        <div class="layout-overlay layout-menu-toggle"></div>
        <!-- Drag Target Area To SlideIn Menu On Small Screens -->
        <div class="drag-target"></div>
    </div>
    <!-- / Layout wrapper -->

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="{{ asset('vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('vendor/libs/node-waves/node-waves.js') }}"></script>
    <script src="{{ asset('vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
    <script src="{{ asset('vendor/libs/hammer/hammer.js') }}"></script>
    <script src="{{ asset('vendor/libs/i18n/i18n.js') }}"></script>
    <script src="{{ asset('vendor/libs/typeahead-js/typeahead.js') }}"></script>
    <script src="{{ asset('vendor/js/menu.js') }}"></script>

    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="{{ asset('vendor/libs/moment/moment.js') }}"></script>
    <script src="{{ asset('vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
    <script src="{{ asset('vendor/libs/select2/select2.js') }}"></script>
    <script src="{{ asset('vendor/libs/%40form-validation/umd/bundle/popular.min.js') }}"></script>
    <script src="{{ asset('vendor/libs/%40form-validation/umd/plugin-bootstrap5/index.min.js') }}"></script>
    <script src="{{ asset('vendor/libs/%40form-validation/umd/plugin-auto-focus/index.min.js') }}"></script>
    <script src="{{ asset('vendor/libs/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
    <script src="{{ asset('vendor/libs/block-ui/block-ui.js') }}"></script>
    <script src="{{ asset('vendor/libs/quill/katex.js') }}"></script>
    <script src="{{ asset('vendor/libs/bootstrap-select/bootstrap-select.js') }}"></script>
    <script src="{{ asset('vendor/libs/quill/quill.js') }}"></script>
    <script src="{{ asset('vendor/libs/sweetalert2/sweetalert2.js') }}"></script>

    <!-- Main JS -->
    <script src="{{ asset('js/main.js') }}"></script>

    @yield('scripts')

</body>

</html>

<!-- beautify ignore:end -->
