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

    <!-- Select Cashier -->
    <div class="modal fade" id="selectBox" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-simple modal-upgrade-plan">
            <div class="modal-content p-3 p-md-5">
                <div class="modal-body p-1">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="text-center py-3">
                        <h3 class="mb-2 pb-1">Seleccionar Caja</h3>
                    </div>
                    <div class="row mx-0 gy-3">
                        <div class="col-xl mb-md-0 mb-4">
                            <div class="card border rounded shadow-none">
                                <div class="card-body">
                                    <h3 class="card-title text-center text-capitalize mb-1">CABAÑA 1</h3>
                                    <div class="row">
                                        <div class="col-md-6 mb-md-0 mb-2  gap-2 py-3">
                                            <div class="form-check custom-option custom-option-icon">
                                                <label class="form-check-label custom-option-content"
                                                    for="customCheckboxIcon1">
                                                    <span class="custom-option-body">
                                                        <i class="mdi mdi-cash-register"></i>
                                                        <span class="custom-option-title"> CAJA ING 1 </span>

                                                    </span>
                                                    <input class="form-check-input" type="checkbox" value=""
                                                        id="customCheckboxIcon1" checked="">
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-md-0 mb-2  gap-2 py-3">
                                            <div class="form-check custom-option custom-option-icon">
                                                <label class="form-check-label custom-option-content"
                                                    for="customCheckboxIcon2">
                                                    <span class="custom-option-body">
                                                        <i class="mdi mdi-cash-register"></i>
                                                        <span class="custom-option-title"> CAJA ING 2 </span>

                                                    </span>
                                                    <input class="form-check-input" type="checkbox" value=""
                                                        id="customCheckboxIcon2">
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-check custom-option custom-option-icon">
                                                <label class="form-check-label custom-option-content"
                                                    for="customCheckboxIcon3">
                                                    <span class="custom-option-body">
                                                        <i class="mdi mdi-cash-register"></i>
                                                        <span class="custom-option-title"> CAJA ING 3 </span>

                                                    </span>
                                                    <input class="form-check-input" type="checkbox" value=""
                                                        id="customCheckboxIcon3">
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-check custom-option custom-option-icon">
                                                <label class="form-check-label custom-option-content"
                                                    for="customCheckboxIcon3">
                                                    <span class="custom-option-body">
                                                        <i class="mdi mdi-cash-register"></i>
                                                        <span class="custom-option-title"> CAJA ING 4 </span>

                                                    </span>
                                                    <input class="form-check-input" type="checkbox" value=""
                                                        id="customCheckboxIcon3">
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl mb-md-0 mb-4">
                            <div class="card border rounded shadow-none">
                                <div class="card-body">
                                    <h3 class="card-title text-center text-capitalize mb-1">CABAÑA 2</h3>
                                    <div class="row">
                                        <div class="col-md-6 mb-md-0 mb-2  gap-2 py-3">
                                            <div class="form-check custom-option custom-option-icon">
                                                <label class="form-check-label custom-option-content"
                                                    for="customCheckboxIcon1">
                                                    <span class="custom-option-body">
                                                        <i class="mdi mdi-cash-register"></i>
                                                        <span class="custom-option-title"> CAJA ING 5 </span>

                                                    </span>
                                                    <input class="form-check-input" type="checkbox" value=""
                                                        id="customCheckboxIcon1" checked="">
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-md-0 mb-2  gap-2 py-3">
                                            <div class="form-check custom-option custom-option-icon">
                                                <label class="form-check-label custom-option-content"
                                                    for="customCheckboxIcon2">
                                                    <span class="custom-option-body">
                                                        <i class="mdi mdi-cash-register"></i>
                                                        <span class="custom-option-title"> CAJA ING 6 </span>

                                                    </span>
                                                    <input class="form-check-input" type="checkbox" value=""
                                                        id="customCheckboxIcon2">
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-check custom-option custom-option-icon">
                                                <label class="form-check-label custom-option-content"
                                                    for="customCheckboxIcon3">
                                                    <span class="custom-option-body">
                                                        <i class="mdi mdi-cash-register"></i>
                                                        <span class="custom-option-title"> CAJA ING 7 </span>

                                                    </span>
                                                    <input class="form-check-input" type="checkbox" value=""
                                                        id="customCheckboxIcon3">
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-check custom-option custom-option-icon">
                                                <label class="form-check-label custom-option-content"
                                                    for="customCheckboxIcon3">
                                                    <span class="custom-option-body">
                                                        <i class="mdi mdi-cash-register"></i>
                                                        <span class="custom-option-title"> CAJA ING 8 </span>

                                                    </span>
                                                    <input class="form-check-input" type="checkbox" value=""
                                                        id="customCheckboxIcon3">
                                                </label>
                                            </div>
                                        </div>
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
