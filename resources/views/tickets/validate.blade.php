@extends('layouts/layout')

@section('content')
    <!-- Content -->


    <!-- Content wrapper -->
    <div class="content-wrapper">
        
        <!-- Content -->
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="col-12 card-numbers-tickets">
                <div class="card">
                    <div class="card-widget-separator-wrapper">
                        <div class="card-body card-widget-separator">
                            <div class="row gy-4 gy-sm-1">
                                <div class="col-sm-6 col-lg-3">
                                    <div
                                        class="d-flex justify-content-between align-items-start card-widget-1 border-end pb-3 pb-sm-0">
                                        <div>
                                            <h3 class="mb-1" id="totalTicky"></h3>
                                            <p class="mb-0">Total</p>
                                        </div>
                                        <div class="avatar me-sm-4">
                                            <span class="avatar-initial rounded bg-label-secondary">
                                                <i class="mdi mdi mdi-ticket-account text-heading mdi-20px"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <hr class="d-none d-sm-block d-lg-none me-4">
                                </div>
                                <div class="col-sm-6 col-lg-3">
                                    <div
                                        class="d-flex justify-content-between align-items-start card-widget-2 border-end pb-3 pb-sm-0">
                                        <div>
                                            <h3 class="mb-1" id="validateTicky"></h3>
                                            <p class="mb-0">Validados</p>
                                        </div>
                                        <div class="avatar  me-lg-4">
                                            <span class="avatar-initial rounded bg-label-secondary">
                                                <i class="mdi mdi-ticket-confirmation text-heading mdi-20px"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <hr class="d-none d-sm-block d-lg-none">
                                </div>
                                <div class="col-sm-6 col-lg-3">
                                    <div
                                        class="d-flex justify-content-between align-items-start border-end pb-3 pb-sm-0 card-widget-3">
                                        <div>
                                            <h3 class="mb-1" id="noValidateTicky"></h3>
                                            <p class="mb-0">No Validados</p>
                                        </div>
                                        <div class="avatar me-sm-4">
                                            <span class="avatar-initial rounded bg-label-secondary">
                                                <i class="mdi mdi mdi-ticket-outline text-heading mdi-20px"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
                <div class="col-lg-3 col-sm-6">
                    <div class="card">
                        <div class="row">
                            <div class="col-6 d-flex align-items-center">
                                <div class="card-body">
                                    <div class="card-info mb-3 pb-2">
                                        <h3 class="mb-3 text-nowrap" id="cashierName"></h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 d-flex align-items-end justify-content-end"> <!-- Cambié 'text-end' por 'justify-content-end' -->
                                <div class="card-body pb-0 pt-3">
                                    <img src="{{ asset('img/illustrations/card-session-illustration.png') }}" alt="Ratings" class="img-fluid" width="81">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
        </div>
        <!-- / Content -->
        <div class="content-backdrop fade"></div>
    </div>
    <!-- Content wrapper -->

    <!-- Select Cashier -->
    <div class="modal fade" id="selectBox" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-simple modal-upgrade-plan">
            <div class="modal-content p-3 p-md-5">
                <div class="modal-body p-1">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="text-center py-3">
                        <h3 class="mb-2 pb-1">Seleccionar Caja</h3>
                    </div>
                    <div class="row mx-0 gy-3">
                        <div class="col-xl mb-md-0 mb-4" id="cashiers1">
                            <div class="card border rounded shadow-none ">
                                <div class="card-body">
                                    <h3 class="card-title text-center text-capitalize mb-1">CABAÑA 1</h3>
                                    <div class="row">

                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl mb-md-0 mb-4" id="cashiers2">
                            <div class="card border rounded shadow-none">
                                <div class="card-body">
                                    <h3 class="card-title text-center text-capitalize mb-1">CABAÑA 2</h3>
                                    <div class="row">

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--/ Select Cashier -->
    <!-- / Content -->
@endsection()

@section('styles')
@endsection()

@section('scripts')
    <script src="{{ asset('js/app-generate-list-validate.js') }}"></script>
@endsection
