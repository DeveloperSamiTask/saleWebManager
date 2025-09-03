@extends('layouts/layout')

@section('content')
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">

        <div class="row invoice-preview">
            <!-- Invoice -->
            <div class="col-xl-12 col-md-8 col-12 mb-md-0 mb-4">
                <div class="card invoice-preview-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between flex-xl-row flex-md-column flex-sm-row flex-column">
                            <div class="mb-xl-0 pb-3">
                                <div class="d-flex svg-illustration align-items-center gap-2 mb-4">
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
                                                    <lineargradient id="paint0_linear_2989_100980" x1="5.36642"
                                                        y1="0.849138" x2="10.532" y2="24.104"
                                                        gradientunits="userSpaceOnUse">
                                                        <stop offset="0" stop-opacity="1"></stop>
                                                        <stop offset="1" stop-opacity="0"></stop>
                                                    </lineargradient>
                                                    <lineargradient id="paint1_linear_2989_100980" x1="5.19475"
                                                        y1="0.849139" x2="10.3357" y2="24.1155"
                                                        gradientunits="userSpaceOnUse">
                                                        <stop offset="0" stop-opacity="1"></stop>
                                                        <stop offset="1" stop-opacity="0"></stop>
                                                    </lineargradient>
                                                </defs>
                                            </svg>
                                        </span>
                                    </span>
                                    <span class="h4 mb-0 app-brand-text fw-bold">Materialize</span>
                                </div>
                            </div>
                            <div>
                                <h4 class="fw-medium">Nº COMPRA: <b id="code"></b></h4>
                                <div class="mb-1">
                                    <span>Fecha Emitido:</span>
                                    <span id="d_purchase"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr class="my-0">
                    <div class="card-body">
                        <div class="d-flex justify-content-between flex-wrap">
                            <div class="my-3">
                                <table>
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
                        </div>
                    </div>
                    <div class="card-header p-0">
                        <div class="nav-align-top">
                            <ul class="nav nav-tabs nav-fill" role="tablist">
                                <li class="nav-item">
                                    <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab"
                                        data-bs-target="#navs-justified-home" aria-controls="navs-justified-home"
                                        aria-selected="true">
                                        <i class="tf-icons mdi mdi-ticket-account me-1"></i>
                                        Entradas
                                    </button>
                                </li>
                                <li class="nav-item">
                                    <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                                        data-bs-target="#navs-justified-profile" aria-controls="navs-justified-profile"
                                        aria-selected="false">
                                        <i class="tf-icons mdi mdi-food-outline me-1"></i>
                                        Alimentos
                                    </button>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="tab-content p-0">
                        <div class="tab-pane fade show active" id="navs-justified-home" role="tabpanel">
                            <div class="table-responsive text-nowrap">
                                <div class="card-datatable table-responsive">
                                    <table class="dt-row-grouping table table-bordered">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th>Nombres y Apellidos</th>
                                                <th></th>
                                                <th>DNI</th>
                                                <th>Usuario</th>
                                                <th>Fecha Uso</th>
                                                <th>Estado</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-md-0 mb-3">

                                    </div>
                                    <div class="col-md-6 d-flex justify-content-md-end mt-2">
                                        <div class="invoice-calculations">
                                            <div class="d-flex justify-content-between">
                                                <span class="w-px-150">Total:</span>
                                                <h6 class="mb-0 pt-1" id="total"> </h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr class="my-0">
                        </div>
                        <div class="tab-pane fade" id="navs-justified-profile" role="tabpanel">
                            <table class="table_combos table table-bordered">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Combos</th>
                                        <th>Cantidad</th>
                                        <th>Validados</th>
                                        <th>Estado</th>
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

    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="editForm">
                    <div class="modal-header">
                        <h5 class="modal-title">Editar Cliente</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="editId" name="id">

                        <div class="mb-3">
                            <label for="editName" class="form-label">Nombres</label>
                            <input type="text" class="form-control" id="editName" name="name" required>
                        </div>

                        <div class="mb-3">
                            <label for="editDni" class="form-label">DNI</label>
                            <input type="text" class="form-control" id="editDni" name="dni" required>
                        </div>

                        <div class="form-check form-switch">
                            <label class="switch">
                                <input type="checkbox" class="switch-input" id="editActive" name="is_active"> <span
                                    class="switch-toggle-slider">
                                    <span class="switch-on"></span>
                                    <span class="switch-off"></span>
                                </span>
                                <span class="switch-label">ESTADO</span>
                            </label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection()

@section('styles')
@endsection()

@section('scripts')
    <script>
        let urlParts = window.location.pathname.split('/');
        let code = urlParts[urlParts.length - 1];

    </script>
    <script src="{{ asset('js/show-courtesy.js') }}?v={{ time() }}"></script>
@endsection
