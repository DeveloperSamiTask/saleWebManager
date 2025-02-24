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
                            <th>CODIGO</th>
                            <th>Nombres y Apellidos</th>
                            <th>Numero Doc.</th>
                            <th>Fecha Inicio</th>
                            <th>Fecha Vencimiento</th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <!-- / Content -->
    <!-- Add New Address Modal -->
    <div class="modal fade" id="addNewCoupon" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-simple modal-dialog-centered">
            <div class="modal-content p-3 p-md-5">
                <div class="modal-body p-md-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="text-center mb-4">
                        <h3 class="address-title mb-2 pb-1">Agregar Socio</h3>
                    </div>
                    <form id="partnerForm" class="row g-4" onsubmit="return false">
                        <div class="col-4">
                            <div class="form-floating form-floating-outline">
                                <input type="text" id="code" name="code" class="form-control"
                                    placeholder="Código" disabled>
                                <label for="code">Código</label>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-floating form-floating-outline">
                                <input type="text" id="pattername" name="pattername" class="form-control"
                                    placeholder="Apellido Paterno">
                                <label for="pattername">Apellido Paterno</label>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-floating form-floating-outline">
                                <input type="text" id="mattername" name="mattername" class="form-control"
                                    placeholder="Apellido Materno">
                                <label for="matternamea">Apellido Materno</label>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-floating form-floating-outline">
                                <input type="text" id="names" name="names" class="form-control"
                                    placeholder="Nombres">
                                <label for="names">Nombres</label>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-floating form-floating-outline">
                                <input type="text" id="doc" name="doc" class="form-control"
                                    placeholder="Número de Documento">
                                <label for="doc">Número Documento</label>
                            </div>
                        </div>

                        <div class="col-4">
                            <div class="form-floating form-floating-outline">
                                <input type="text" class="form-control flatpickr-date" placeholder="YYYY-MM-DD"
                                    name=birthdate>
                                <label for="birthdate">Fecha de Nacimiento</label>
                            </div>
                        </div>

                        <div class="col-4">
                            <div class="form-floating form-floating-outline">
                                <input type="text" id="affiliation" name="affiliation" class="form-control"
                                    placeholder="Ficha Afilicación">
                                <label for="affiliation">Ficha Afilicación</label>
                            </div>
                        </div>

                        <div class="col-4">
                            <div class="form-floating form-floating-outline">
                                <input type="text" class="form-control flatpickr-date" placeholder="YYYY-MM-DD"
                                    name="initdate" id="initdate">
                                <label for="initdate">Inicio Socio</label>
                            </div>
                        </div>

                        <div class="col-4">
                            <div class="form-floating form-floating-outline">
                                <input type="text" class="form-control flatpickr-date" placeholder="YYYY-MM-DD"
                                    name="enddate" id="enddate">
                                <label for="enddate">Vencimiento</label>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-floating form-floating-outline">
                                <input type="text" id="address" name="address" class="form-control"
                                    placeholder="Dirección">
                                <label for="address">Dirección</label>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-floating form-floating-outline">
                                <input type="text" id="phone" name="phone" class="form-control"
                                    placeholder="Número Celular">
                                <label for="phone">Número Celular</label>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-floating form-floating-outline">
                                <input type="text" id="mail" name="mail" class="form-control"
                                    placeholder="E-mail">
                                <label for="mail">E-mail</label>
                            </div>
                        </div>
                        <div class="col-12 text-center">
                            <button type="submit" class="btn btn-primary me-sm-3 me-1">Agregar</button>
                            <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal"
                                aria-label="Close">Cancelar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection()

@section('styles')
    <link rel="stylesheet" href="{{ asset('vendor/libs/flatpickr/flatpickr.css') }}">
    <style>
        .image-wrapper {
            text-align: center;
        }

        .preview-image {
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 5px;
            transition: transform 0.2s;
        }

        .preview-image:hover {
            transform: scale(1.1);
            cursor: pointer;
        }
    </style>
@endsection()

@section('scripts')
    <script src="{{ asset('vendor/libs/flatpickr/flatpickr.js') }}"></script>
    <script src="{{ asset('js/partners.js') }}"></script>
@endsection
