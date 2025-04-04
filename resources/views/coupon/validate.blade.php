@extends('layouts/layout')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="py-3 mb-4">
        </h4>
        <!-- Custom Icon Radios -->
        <div class="row">

            <div class="col-xl-12 mb-4">
                <div class="row">
                    <div class="col-md mb-md-0 mb-2">
                        <div class="form-check custom-option custom-option-icon">
                            <input name="customRadioIcon-01" class="form-check-input d-none tab-radio" type="radio"
                                id="customRadioIcon1" data-target="#tab-starter" checked>
                            <label class="form-check-label custom-option-content" for="customRadioIcon1">
                                <span class="custom-option-body">
                                    <i class="mdi mdi-gift-open-outline"></i>
                                    <span class="custom-option-title">Paquete Cumpleaños</span>
                                    <small>Validar cupones de Paq. Cumpleaños.</small>
                                </span>
                            </label>
                        </div>
                    </div>
                    <div class="col-md mb-md-0 mb-2">
                        <div class="form-check custom-option custom-option-icon">
                            <input name="customRadioIcon-01" class="form-check-input d-none tab-radio" type="radio"
                                id="customRadioIcon2" data-target="#tab-personal" disabled>
                            <label class="form-check-label custom-option-content" for="customRadioIcon2">
                                <span class="custom-option-body">
                                    <i class="mdi mdi-domain"></i>
                                    <span class="custom-option-title">Empresas</span>
                                    <small>Validar Cupones de Empresa</small>
                                </span>
                            </label>
                        </div>
                    </div>
                    <div class="col-md">
                        <div class="form-check custom-option custom-option-icon">
                            <input name="customRadioIcon-01" class="form-check-input d-none tab-radio" type="radio"
                                id="customRadioIcon3" data-target="#tab-enterprise" disabled>
                            <label class="form-check-label custom-option-content" for="customRadioIcon3">
                                <span class="custom-option-body">
                                    <i class="mdi mdi-town-hall"></i>
                                    <span class="custom-option-title">Colegios</span>
                                    <small>Validar Cupones de Colegios.</small>
                                </span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contenido de los tabs -->
            <div class="tab-content">
                <div id="tab-starter" class="tab-pane fade show active">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div
                                    class="card-header sticky-element bg-label-secondary d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row">
                                    <h5 class="card-title mb-sm-0 me-2">Datos de la Reserva</h5>
                                </div>
                                <div class="card-body">
                                    <form id="formCoupon">
                                        <div class="row">
                                            <div id="coupon-alert"></div>
                                            <div class="col-lg-10 mx-auto">
                                                <h5 class="mb-4">Información Cupón</h5>
                                                <div class="row g-3">
                                                    <div class="col-md-3">
                                                        <div class="input-group input-group-merge">
                                                            <span class="input-group-text"><i
                                                                    class="mdi mdi-qrcode fs-3"></i></span>
                                                            <div class="form-floating form-floating-outline">
                                                                <input type="text" class="form-control" id="code"
                                                                    placeholder="Código">
                                                                <label for="code">Código</label>
                                                            </div>
                                                            <span class="input-group-text cursor-pointer" id="searchCode"><i
                                                                    class="mdi mdi-file-search fs-3"></i></span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="input-group input-group-merge">
                                                            <span class="input-group-text"><i
                                                                    class="mdi mdi-ticket-percent fs-3"></i></span>
                                                            <div class="form-floating form-floating-outline">
                                                                <input type="text" class="form-control" id="promotion"
                                                                    placeholder="Descripción de Promoción">
                                                                <label for="promotion">Promoción</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="input-group input-group-merge">
                                                            <span class="input-group-text"><i
                                                                    class="mdi mdi-calendar-month-outline fs-3"></i></span>
                                                            <div class="form-floating form-floating-outline">
                                                                <input type="text" class="form-control" id="expiration"
                                                                    placeholder="Expiración">
                                                                <label for="expiration">Fecha/Hora Expiración</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr />
                                                <h5 class="my-4">Información Beneficiario</h5>
                                                <div class="row gy-3">
                                                    <div class="col-md-4">
                                                        <div class="input-group input-group-merge">
                                                            <span class="input-group-text"><i
                                                                    class="mdi mdi-smart-card-outline fs-3"></i></span>
                                                            <div class="form-floating form-floating-outline">
                                                                <input type="text" class="form-control" id="typeDoc"
                                                                    placeholder="Cantidad Integrantes">
                                                                <label for="typeDoc">Tipo Documento</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="input-group input-group-merge">
                                                            <span class="input-group-text"><i
                                                                    class="mdi mdi-card-account-details-outline fs-3"></i></span>
                                                            <div class="form-floating form-floating-outline">
                                                                <input type="text" class="form-control" id="numberDoc"
                                                                    placeholder="Cantidad Integrantes">
                                                                <label for="numberDoc">Nº Documento</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <div class="input-group input-group-merge">
                                                            <span class="input-group-text"><i
                                                                    class="mdi mdi-badge-account fs-3"></i></span>
                                                            <div class="form-floating form-floating-outline">
                                                                <input type="text" class="form-control" id="names"
                                                                    placeholder="Cantidad Integrantes">
                                                                <label for="names">Apellidos y Nombres</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr />
                                                <h5 class="my-4">Observaciones</h5>
                                                <div class="row g-3">
                                                    <div class="col-sm-10 col-8">
                                                        <textarea id="autosize-demo" rows="3" class="form-control" id="observations"></textarea>
                                                    </div>
                                                    <div class="col-sm-2 col-4 d-grid">
                                                        <button class="btn btn-primary" id="validateCoupon">Validar</button>
                                                    </div>
                                                </div>
                                                <hr />
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="tab-personal" class="tab-pane fade">
                    <p>Contenido de Personal</p>
                </div>
                <div id="tab-enterprise" class="tab-pane fade">
                    <p>Contenido de Enterprise</p>
                </div>
            </div>

            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    const radioButtons = document.querySelectorAll(".tab-radio");
                    radioButtons.forEach(radio => {
                        radio.addEventListener("change", function() {
                            document.querySelectorAll(".tab-pane").forEach(tab => tab.classList.remove(
                                "show", "active"));
                            const targetTab = document.querySelector(this.dataset.target);
                            if (targetTab) {
                                targetTab.classList.add("show", "active");
                            }
                        });
                    });
                });
            </script>
        </div>
    @endsection()

    @section('styles')
        <link rel="stylesheet" href="{{ asset('vendor/libs/flatpickr/flatpickr.css') }}">
    @endsection()

    @section('scripts')
        <script src="{{ asset('vendor/libs/autosize/autosize.js') }}"></script>
        <script src="{{ asset('vendor/libs/flatpickr/flatpickr.js') }}"></script>
        <script src="{{ asset('js/validate-coupons.js') }}"></script>
    @endsection
