<!DOCTYPE html>

<html lang="es" class="light-style layout-navbar-fixed layout-menu-fixed layout-compact " dir="ltr"
    data-theme="theme-default" data-assets-path="{{ asset('') }}" data-template="vertical-menu-template">

<head>
    <meta charset="utf-8">
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">

    <title>Validar Pase de Cortesia</title>

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

    <!-- Layout wrapper -->

    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">

        <div class="row invoice-preview">

            <!-- Invoice -->
            <div class="col-xl-12 col-md-8 col-12 mb-md-0">
                <div class="card invoice-preview-card">

                    <!-- HEADER -->
                    <div class="card-header border-bottom-0 pb-0">
                        <div class="d-flex justify-content-between flex-xl-row flex-md-column flex-sm-row flex-column">
                            <!-- Logo + Nombre -->
                            <div class="mb-xl-0 pb-3">
                                <div class="d-flex svg-illustration align-items-center gap-2 mb-4">
                                    <span class="app-brand-logo demo" style="color:var(--bs-primary);">
                                        <!-- tu SVG -->
                                        <svg width="38" height="20" viewBox="0 0 38 20"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <!-- paths recortados -->
                                        </svg>
                                    </span>
                                    <span class="h4 mb-0 app-brand-text fw-bold">PASES DE CORTESIA</span>
                                </div>
                            </div>

                            <!-- Código + Fecha -->
                            <div class="text-end">
                                <h4 class="fw-medium mb-2">CODIGO CUPON: <b id="code"></b></h4>
                                <div>
                                    <span class="fw-medium">Fecha Emitido:</span>
                                    <span id="d_purchase"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="my-0">

                    <!-- BODY -->
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start flex-wrap">

                            <!-- Información del cliente -->
                            <div class="my-3">
                                <table class="table table-borderless mb-0">
                                    <tbody>
                                        <tr>
                                            <td class="pe-3 fw-medium" id="client"></td>
                                        </tr>
                                        <tr>
                                            <td class="pe-3 fw-medium" id="document"></td>
                                        </tr>
                                        <tr>
                                            <td class="pe-3 fw-medium" id="date_issue"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Botones de acción -->
                            <div class="text-end my-3">
                                <button class="btn btn-primary mb-2 w-100" id="btn-approve">
                                    <i class="mdi mdi-check-circle me-1"></i>Aprobar
                                </button>
                                <button class="btn btn-danger w-100" id="btn-cancel">
                                    <i class="mdi mdi-cancel me-1"></i>Anular
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- TABLA DE MIEMBROS -->
                    <div class="card-body pt-0">
                        <div class="card-datatable table-responsive">
                            <table class="dt-row-grouping table table-bordered mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th></th>
                                        <th>Nombres y Apellidos</th>
                                        <th></th>
                                        <th>DNI</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>

                </div>
            </div>

        </div>
        <div class="content-backdrop fade"></div>
    </div>

    <!-- / Content -->


    <!-- Overlay -->
    <!-- Drag Target Area To SlideIn Menu On Small Screens -->
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
    <script src="{{ asset('vendor/libs/block-ui/block-ui.js') }}"></script>
    <script src="{{ asset('vendor/libs/sweetalert2/sweetalert2.js') }}"></script>

    <!-- Main JS -->
    <script src="{{ asset('js/main.js') }}"></script>

    <script>
        let urlParts = window.location.pathname.split('/');
        let code = urlParts[urlParts.length - 1];
    </script>
    <script src="{{ asset('js/authorize_courtesyfdt.js') }}?v={{ time() }}"></script>

</body>

</html>

<!-- beautify ignore:end -->
