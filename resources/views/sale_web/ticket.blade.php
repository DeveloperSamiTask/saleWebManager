@extends('layouts/layout')

@section('content')
    <!-- Content -->


    <!-- Content wrapper -->
    <div class="content-wrapper">

        <!-- Content -->

        <div class="container-xxl flex-grow-1 container-p-y">


            <h4 class="py-3 mb-2">TICKETERA WEB</h4>

            <p class="mb-4">Each category (Basic, Professional, and Business) includes the four predefined roles shown
                below.</p>


            <!-- Permission Table -->
            <div class="card">
                <div class="card-datatable table-responsive">
                    <table class="datatables-permissions table">
                        <thead class="table-light">
                            <tr>
                                <th></th>
                                <th></th>
                                <th>DNI</th>
                                <th>Nombres y Apellidos</th>
                                <th>Producto</th>
                                <th>Precio</th>
                                <th>Fecha Compra</th>
                                <th>Seguro</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
            <!--/ Permission Table -->


            <!-- Modal -->
            <!-- Add Permission Modal -->
            <div class="modal fade" id="addPermissionModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-simple modal-edit-user modal-dialog-centered">
                    <div class="modal-content p-3 p-md-5">
                        <div class="modal-body py-3 py-md-0">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            <div class="text-center mb-4">
                                <h3 class="mb-2">Información de Entrada</h3>
                                <p class="pt-1">Verificar con el DNI fisico, si es la persona que esta registrada en la
                                    entrada.</p>
                            </div>
                            <div class="alert alert-solid-danger d-flex align-items-center" style="display:none !important"
                                role="alert" id="validate_message">
                                <i class="mdi mdi-alert-circle-outline me-2"></i>
                                Este ticket ya fue usado el dia <b id="date_use"></b>
                            </div>
                            <form class="row g-4" id="validate_cupon" onsubmit="return false">
                                <div class="col-12 col-md-4">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i class="mdi mdi-ticket-confirmation"></i></span>
                                        <div class="form-floating form-floating-outline">
                                            <input type="text" class="form-control" id="ticket" name="ticket"
                                                placeholder="123456" aria-label="ticket">
                                            <label for="ticket">NRO TICKET</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-8">
                                    <div class="form-floating form-floating-outline">
                                        <input type="text" id="names" name="names" class="form-control"
                                            placeholder="123456" disabled>
                                        <label for="names">Nombres y Apellidos</label>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="form-floating form-floating-outline">
                                        <input type="text" id="shift" name="shift" class="form-control"
                                            placeholder="www" disabled>
                                        <label for="shift">Turno</label>
                                    </div>
                                </div>
                                <div class="col-12 col-md-8">
                                    <div class="form-floating form-floating-outline">
                                        <input type="text" id="device" name="device" class="form-control"
                                            placeholder="device" disabled>
                                        <label for="device">Dispositivo</label>
                                    </div>
                                </div>
                                <div class="col-12 col-md-5">
                                    <div class="form-floating form-floating-outline">
                                        <input type="text" id="pruchase_date" name="pruchase_date" class="form-control"
                                            placeholder="john.doe.007" disabled>
                                        <label for="pruchase_date">Fecha de Compra</label>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="form-floating form-floating-outline">
                                        <input type="text" id="admission_date" name="admission_date" class="form-control"
                                            placeholder="john.doe.007" disabled>
                                        <label for="admission_date">Fecha de Ingreso</label>
                                    </div>
                                </div>
                                <div class="col-12 col-md-3">
                                    <div class="form-floating form-floating-outline">
                                        <input type="text" id="price" name="price" class="form-control"
                                            placeholder="S/. 100" disabled>
                                        <label for="price">Precio</label>
                                    </div>
                                </div>
                                <div class="col-12 col-md-3">
                                    <div class="form-floating form-floating-outline">
                                        <input type="text" id="sure" name="sure" class="form-control"
                                            placeholder="john.doe.007" disabled>
                                        <label for="sure">Seguro</label>
                                    </div>
                                </div>

                                <div class="col-12 col-md-4">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i class="mdi mdi-card-account-details"></i></span>
                                        <div class="form-floating form-floating-outline">
                                            <input type="text" class="form-control" id="dni" name="dni"
                                                placeholder="12345678" aria-label="dni">
                                            <label for="dni">Número DNI</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 text-center">
                                    <button type="submit" class="btn btn-primary me-sm-3 me-1 btn_validate"
                                        disabled>Validar</button>
                                    <button type="reset" class="btn btn-outline-danger"
                                        onclick="resetForm()">LIMPIAR</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!--/ Add Permission Modal -->

            <!-- Two Factor Auth Modal -->

            <div class="modal fade" id="twoFactorAuth" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered modal-simple">
                    <div class="modal-content p-3 p-md-5">
                        <div class="modal-body py-3 py-md-0">
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                            <div class="text-center mb-4">
                                <h3 class="mb-2">Selecciona el Método de Envío</h3>

                            </div>
                            <div class="row pt-1">
                                <div class="col-12 mb-3">
                                    <div class="form-check custom-option custom-option-basic custom-option-label checked">
                                        <label class="form-check-label custom-option-content ps-4 py-3"
                                            for="customRadioTemp1" data-bs-target="#twoFactorAuthOne"
                                            data-bs-toggle="modal">
                                            <input name="customRadioTemp" class="form-check-input d-none" type="radio"
                                                value="" id="customRadioTemp1">
                                            <span class="d-flex align-items-center">
                                                <i class="mdi mdi-whatsapp mdi-36px me-3"></i>
                                                <span>
                                                    <span class="custom-option-header">
                                                        <span class="h5 mb-1">WhatsApp</span>
                                                    </span>
                                                    <span class="custom-option-body">
                                                        <span class="mb-0">Usar whtatsapp para enviar el QR de
                                                            activación.</span>
                                                    </span>
                                                </span>
                                            </span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-check custom-option custom-option-basic custom-option-label">
                                        <label class="form-check-label custom-option-content ps-4 py-3" for="print_qr">
                                            <input name="customRadioTemp" class="form-check-input d-none" type="radio"
                                                value="" id="print_qr">
                                            <span class="d-flex align-items-center">
                                                <i class="mdi mdi-printer mdi-36px me-3"></i>
                                                <span>
                                                    <span class="custom-option-header">
                                                        <span class="h5 mb-1">Imprimir QR</span>
                                                    </span>
                                                    <span class="custom-option-body">
                                                        <span class="mb-0">Imprime ahora el código QR.</span>
                                                    </span>
                                                </span>
                                            </span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Authentication App -->
            <div class="modal fade" id="twoFactorAuthOne" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered modal-simple">
                    <div class="modal-content p-3 p-md-5">
                        <div class="modal-body py-3 py-md-0">
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                            <div class="text-center mb-4">
                                <h3 class="mb-0">Enviar codigo QR por WhatsApp</h3>
                            </div>
                            <div class="mb-4">
                                <div class="input-group input-group-merge">
                                    <span class="input-group-text"><i class="mdi mdi-cellphone"></i></span>
                                    <div class="form-floating form-floating-outline">
                                        <input type="email" class="form-control" id="phoneText" name="phoneText"
                                            placeholder="Número de celular del cliente" aria-label="phoneText">
                                        <label for="phoneText">Número Celular</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 text-end">
                                <button type="submit" class="btn btn-outline-secondary me-sm-3 me-1"
                                    data-bs-toggle="modal" data-bs-target="#twoFactorAuth"><i
                                        class="mdi mdi-arrow-left me-1 scaleX-n1-rtl"></i><span
                                        class="align-middle d-none d-sm-inline-block">Atras</span></button>
                                <button type="button" class="btn btn-primary send-whatsapp"><span
                                        class="align-middle d-none d-sm-inline-block">Continuar</span><i
                                        class="mdi mdi-arrow-right ms-1 scaleX-n1-rtl"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
    <script src="{{ asset('js/app-generate-coupon-permission.js') }}"></script>
@endsection
