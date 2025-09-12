@extends('layouts/layout')

@section('content')
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="py-3 mb-4">
            <span class="text-muted fw-light">Formulario/</span>
            Pase de Cortesía
        </h4>
        <!-- Sticky Actions -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <form id="paymentLinkForm">
                        <div
                            class="card-header sticky-element bg-label-secondary d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row">
                            <h5 class="card-title mb-sm-0 me-2">Formulario Pase de Cortesía</h5>
                            <div class="action-btns">
                                <button class="btn btn-primary">Guardar Pase</button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-9 mx-auto">
                                    <h5 class="mb-4">1. Informacion de Compra</h5>
                                    <div class="row g-4">
                                        <div class="col-md-4">
                                            <div class="form-floating form-floating-outline">
                                                <input class="form-control" type="text" id="lastname" name="lastname"
                                                    placeholder="Apellidos" aria-label="Apellidos"
                                                    aria-describedby="Apellidos" />
                                                <label for="lastname">Apellidos</label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-floating form-floating-outline">
                                                <input type="text" id="names" name="names"
                                                    class="form-control phone-mask" placeholder="Nombres"
                                                    aria-label="Nombres" />
                                                <label for="names">Nombres</label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-floating form-floating-outline">
                                                <select id="document" name="document" class="select2 form-select"
                                                    data-allow-clear="true" data-placeholder="Seleccione Tipo Documento">
                                                    <option value="">Seleccione</option>
                                                    <option value="DNI">DNI</option>
                                                    <option value="RUC">RUC</option>
                                                    <option value="CE">Carné de Extranjería (CE)</option>
                                                    <option value="PASAPORTE">PASAPORTE</option>
                                                    <option value="OTRO">OTRO</option>
                                                </select>
                                                <label for="document">Tipo Documento</label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="input-group input-group-merge">
                                                <span class="input-group-text"><i
                                                        class="mdi mdi-card-account-details fs-3"></i></span>
                                                <div class="form-floating form-floating-outline">
                                                    <input class="form-control" type="text" id="number_doc"
                                                        name="number_doc" placeholder="Número Documento"
                                                        aria-label="Número Documento" aria-describedby="Número Documento" />
                                                    <label for="number_doc">Número Documento</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="input-group input-group-merge">
                                                <span class="input-group-text"><i class="mdi mdi-whatsapp fs-3"></i></span>
                                                <div class="form-floating form-floating-outline">
                                                    <input type="text" id="phone" name="phone" class="form-control"
                                                        placeholder="Número Celular" aria-label="Nombres" />
                                                    <label for="phone">Número Celular</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-12">
                                            <div class="form-floating form-floating-outline">
                                                <input type="text" class="form-control" placeholder="YYYY-MM-DD"
                                                    id="date_issue" name="date_issue" />
                                                <label for="date_issue">Fecha de Ingreso</label>
                                            </div>
                                        </div>

                                        <div class="col-md-12 col-12">
                                            <div class="form-floating form-floating-outline">
                                                <input class="form-control" type="text" id="observation"
                                                    name="observation" placeholder="Observación" aria-label="Observación"
                                                    aria-describedby="Apellidos" />
                                                <label for="observation">Observación</label>
                                            </div>
                                        </div>


                                        <div class="col-md-12 mt-4 mb-3">
                                            <div class="d-flex justify-content-end mb-2">
                                                <button type="button" class="btn btn-primary" id="addCombo">
                                                    <i class="mdi mdi-plus"></i> Agregar Promoción
                                                </button>
                                            </div>

                                            <div class="table-responsive">
                                                <table class="table table-bordered table-striped" id="comboTable">
                                                    <thead class="table-dark">
                                                        <tr>
                                                            <th style="width: 30%">Combo</th>
                                                            <th style="width: 10%">Cantidad</th>
                                                            <th style="width: 10%">Acción</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="comboTableBody">
                                                        <tr class="combo-empty-message">
                                                            <td colspan="3" class="text-center text-muted">📦 Elige tus
                                                                combos para comenzar</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- /Sticky Actions -->
        </div>
    </div>
    <!-- / Content -->
@endsection()

@section('styles')
    <link rel="stylesheet" href="{{ asset('vendor/libs/flatpickr/flatpickr.css') }}">
@endsection()

@section('scripts')
    <script src="{{ asset('vendor/libs/jquery-sticky/jquery-sticky.js') }}"></script>
    <script src="{{ asset('vendor/libs/flatpickr/flatpickr.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>
    <script src="{{ asset('js/add-courtesy.js') }}?v={{ time() }}"></script>
    <script>
        const combos = @json($promotions); // {id, name, members}
    </script>
@endsection
