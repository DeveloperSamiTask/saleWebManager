@extends('layouts/layout')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">


        <div class="col-12 card-numbers-tickets mb-4">
            <div class="card">
                <div class="card-widget-separator-wrapper">
                    <div class="card-body card-widget-separator">
                        <div class="row gy-4 gy-sm-1">
                            <div class="col-sm-6 col-lg-3">
                                <div
                                    class="d-flex justify-content-between align-items-start card-widget-1 border-end pb-3 pb-sm-0">
                                    <div>
                                        <h3 class="mb-1" id="totalTicky">{{ $data['paymentLink_total'] }}</h3>
                                        <p class="mb-0">Total</p>
                                    </div>
                                    <div class="avatar me-sm-4">
                                        <span class="avatar-initial rounded bg-label-secondary">
                                            <i class="mdi mdi-ticket-outline text-heading mdi-20px"></i>
                                        </span>
                                    </div>
                                </div>
                                <hr class="d-none d-sm-block d-lg-none me-4">
                            </div>
                            <div class="col-sm-6 col-lg-3">
                                <div
                                    class="d-flex justify-content-between align-items-start card-widget-2 border-end pb-3 pb-sm-0">
                                    <div>
                                        <h3 class="mb-1" id="validateTicky">{{ $data['paymentLink_validados'] }}</h3>
                                        <p class="mb-0">Validados</p>
                                    </div>
                                    <div class="avatar  me-lg-4">
                                        <span class="avatar-initial rounded bg-label-secondary">
                                            <i class="mdi mdi-ticket-confirmation-outline text-heading mdi-20px"></i>
                                        </span>
                                    </div>
                                </div>
                                <hr class="d-none d-sm-block d-lg-none">
                            </div>
                            <div class="col-sm-6 col-lg-3">
                                <div
                                    class="d-flex justify-content-between align-items-start border-end pb-3 pb-sm-0 card-widget-3">
                                    <div>
                                        <h3 class="mb-1" id="noValidateTicky">
                                            {{ $data['paymentLink_total'] - $data['paymentLink_validados'] }}</h3>
                                        <p class="mb-0">No Validados</p>
                                    </div>
                                    <div class="avatar me-sm-4">
                                        <span class="avatar-initial rounded bg-label-secondary">
                                            <i class="mdi mdi-ticket-account text-heading mdi-20px"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

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
            <div class="col-lg-7 col-sm-12">
                <div class="card">
                    <div class="row">
                        <div class="col-8 d-flex align-items-center">
                            <div class="card-body">
                                <div class="card-info mb-3 pb-2">
                                    <h5 class="mb-3 text-nowrap" id="names"></h5>
                                    <h5 class="mb-3 text-nowrap" id="documento"></h5>
                                    <div id="status"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-4 d-flex align-items-end justify-content-end">
                            <!-- Cambié 'text-end' por 'justify-content-end' -->
                            <div class="card-body pb-0 pt-3">
                                <img src="{{ asset('img/illustrations/card-session-illustration.png') }}" alt="Ratings"
                                    class="img-fluid" width="81">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="alertCard"></div>

        <!-- Row grouping -->
        <div class="card">
            <div class="card-datatable table-responsive">
                <table class="dt-row-grouping table table-bordered">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Nombres y Apellidos</th>
                            <th>DNI</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
        <!--/ Row grouping -->
    </div>

    <div class="modal fade" id="validateModal" tabindex="-1" aria-labelledby="validateModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="validateModalLabel">Validar Código</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <form id="formValidateDni">
                        <div class="modal-body">
                            <input type="hidden" id="hiddenRecordId" name="record_id">
                            <p class="fs-5"><strong>Nombre:</strong> <span id="modalName"></span></p>
                            <div class="mb-3">
                                <label for="inputDni" class="form-label">Ingrese el DNI:</label>
                                <input type="text" class="form-control" id="inputDni" name="input_dni" required>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Validar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection()

@section('styles')
    <link rel="stylesheet" href="{{ asset('vendor/libs/datatables-rowgroup-bs5/rowgroup.bootstrap5.css') }}">
    <style>
        .table-success {
            background-color: #d4edda !important;
        }
    </style>
@endsection()

@section('scripts')
    <script src="{{ asset('js/table-validate-payment.js') }}?v={{ time() }}"></script>
@endsection
