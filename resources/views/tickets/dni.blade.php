@extends('layouts/layout')

@section('content')
    <!-- Content -->


    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="card-header border-bottom">
                <h5 class="card-title">Filtro de búsqueda</h5>
                <div class="d-flex justify-content-between align-items-center row py-3 gap-3 gap-md-0">
                    <div class="col-md-3">
                        <div class="form-floating form-floating-outline">
                            <input type="text" class="form-control" placeholder="YYYY-MM-DD to YYYY-MM-DD"
                                id="flatpickr-range">
                            <label for="flatpickr-range">Rango de Fechas</label>
                        </div>
                    </div>
                    <div class="col-md-3 user_role"></div>
                    <div class="col-md-3 user_plan"></div>
                    <div class="col-md-3 user_status"></div>
                </div>
            </div>
            <div class="card-datatable table-responsive">
                <table class="datatables-entries table">
                    <thead class="table-light">
                        <tr>
                            <th></th>
                            <th>Entrada</th>
                            <th>Nombres y Apelldios</th>
                            <th>Enviado Por</th>
                            <th>Doc. Erroneo</th>
                            <th>Doc. Correcto</th>
                            <th>Estado</th>
                            <th>Aceptado Por</th>
                            <th>Fecha de Solicitud</th>
                            <th>Fecha de Cambio</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <!-- / Content -->
    <!-- / Content -->
@endsection()

@section('styles')
    <link rel="stylesheet" href="{{ asset('vendor/libs/flatpickr/flatpickr.css') }}">
@endsection()

@section('scripts')
    <script src="{{ asset('vendor/libs/flatpickr/flatpickr.js') }}"></script>
    <script src="{{ asset('js/app-generate-list-logs.js') }}"></script>
@endsection
