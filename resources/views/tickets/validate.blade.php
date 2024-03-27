@extends('layouts/layout')

@section('content')
    <!-- Content -->


    <!-- Content wrapper -->
    <div class="content-wrapper">
        <!-- Content -->
        <div class="container-xxl flex-grow-1 container-p-y">
            <h4 class="py-3 mb-2">Validar Lista de Entradas</h4>
            <div class="row mb-4 g-4">
                <div class="col-lg-5">
                    <div class="card h-100">
                        <div class="card-body">
                            <form class="referral-form" onsubmit="return false">
                                <div class="mb-4 mt-1">
                                    <div class="row g-2">
                                        <div class="col-sm-9 col-lg-8">
                                            <div class="form-floating form-floating-outline me-3">
                                                <input type="text" id="qrCode" name="qrCode" class="form-control"
                                                    placeholder="Escanea Código QR Aqui">
                                                <label class="mb-0" for="qrCode">Escanea Código QR Aqui</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Permission Table -->
            <div class="card">
                <div class="card-datatable table-responsive">
                    <table class="datatables-permissions table">
                        <thead class="table-light">
                            <tr>
                                <th></th>
                                <th>Ticket</th>
                                <th>DNI</th>
                                <th>Nombres y Apellidos</th>
                                <th>Entrada</th>
                                <th>Dispositivo</th>
                                <th>Precio</th>
                                <th>Fecha Ingreso</th>
                                <th>Seguro</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
            <!--/ Permission Table -->
        </div>
        <!-- / Content -->
        <div class="content-backdrop fade"></div>
    </div>
    <!-- Content wrapper -->
    <!-- / Content -->
@endsection()

@section('styles')
@endsection()

@section('scripts')
    <script src="{{ asset('js/app-generate-list-validate.js') }}"></script>
@endsection
