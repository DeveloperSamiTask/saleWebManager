@extends('layouts/layout')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">

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
        <div  id="alertCard"></div>

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
@endsection()

@section('scripts')
    <script src="{{ asset('js/table-validate-payment.js') }}"></script>
@endsection
