@extends('layouts/layout')

@section('content')
    <!-- Content -->
    <div class="content-wrapper">

        <!-- Content -->

        <div class="container-xxl flex-grow-1 container-p-y">


            <h4 class="py-3 mb-4">
                Panel de Control
            </h4>

            <!-- Card Border Shadow -->
            <div class="row">
                <div class="col-sm-6 col-lg-3 mb-4">
                    <div class="card card-border-shadow-primary h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-2 pb-1">
                                <div class="avatar me-2">
                                    <span class="avatar-initial rounded bg-label-primary"><i
                                            class="mdi mdi-bus-school mdi-20px"></i></span>
                                </div>
                                <h4 class="ms-1 mb-0 display-6" id="total"></h4>
                            </div>
                            <p class="mb-0 text-heading">Total de Entradas</p>

                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3 mb-4">
                    <div class="card card-border-shadow-warning h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-2 pb-1">
                                <div class="avatar me-2">
                                    <span class="avatar-initial rounded bg-label-warning">
                                        <i class='mdi mdi-alert mdi-20px'></i></span>
                                </div>
                                <h4 class="ms-1 mb-0 display-6">8</h4>
                            </div>
                            <p class="mb-0 text-heading">Turno Completo</p>

                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3 mb-4">
                    <div class="card card-border-shadow-danger h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-2 pb-1">
                                <div class="avatar me-2">
                                    <span class="avatar-initial rounded bg-label-danger">
                                        <i class='mdi mdi-source-fork mdi-20px'></i>
                                    </span>
                                </div>
                                <h4 class="ms-1 mb-0 display-6">27</h4>
                            </div>
                            <p class="mb-0 text-heading">After School</p>

                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3 mb-4">
                    <div class="card card-border-shadow-info h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-2 pb-1">
                                <div class="avatar me-2">
                                    <span class="avatar-initial rounded bg-label-info"><i
                                            class='mdi mdi-timer-outline mdi-20px'></i></span>
                                </div>
                                <h4 class="ms-1 mb-0 display-6">13</h4>
                            </div>
                            <p class="mb-0 text-heading">Late vehicles</p>
                        </div>
                    </div>
                </div>
            </div>
            <!--/ Card Border Shadow -->
            <div class="row">
                <!-- Shipment statistics-->
                <div class="col-lg-12 col-xxl-12 mb-4 order-3 order-xxl-1">
                    <div class="card">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <div class="card-title mb-0">
                                <h5 class="m-0 me-2 mb-1">Entradas Vendidas</h5>
                            </div>
                            <div class="col-md-3">
                                <div class="form-floating form-floating-outline">
                                    <input type="text" class="form-control" placeholder="YYYY-MM-DD to YYYY-MM-DD"
                                        id="flatpickr-range">
                                    <label for="flatpickr-range">Rango de Fechas</label>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="shipmentStatisticsChart"></div>
                        </div>
                    </div>
                </div>
                <!--/ Shipment statistics -->

                <!-- On route vehicles Table -->
                <div class="col-12 order-5">
                    <div class="card">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <div class="card-title mb-0">
                                <h5 class="m-0 me-2">Entradas Validadas</h5>
                            </div>
                        </div>
                        <div class="card-datatable table-responsive">
                            <table class="dt-route-vehicles table">
                                <thead class="table-light">
                                    <tr>
                                        <th>Entrada</th>
                                        <th>Cliente</th>
                                        <th>Turno</th>
                                        <th>Dispositivo</th>
                                        <th>Precio</th>
                                        <th class="w-20">Ingreso</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!--/ On route vehicles Table -->


        </div>
        <!-- / Content -->



        <div class="content-backdrop fade"></div>
    </div>
    <!-- / Content -->
@endsection()


@section('styles')
    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ asset('vendor/libs/flatpickr/flatpickr.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/libs/apex-charts/apex-charts.css') }}">

    <!-- Page CSS -->
    <link rel="stylesheet" href="{{ asset('vendor/css/pages/app-logistics-dashboard.css') }}">
@endsection()

@section('scripts')
    <!-- Vendors JS -->
    <script src="{{ asset('vendor/libs/flatpickr/flatpickr.js') }}"></script>
    <script src="{{ asset('vendor/libs/apex-charts/apexcharts.js') }}"></script>

    <!-- Page JS -->
    <script src="{{ asset('js/app-logistics-dashboard.js') }}"></script>
@endsection
