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
                                                <i class="mdi mdi-account-outline text-heading mdi-20px"></i>
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
                                                <i class="mdi mdi-content-paste text-heading mdi-20px"></i>
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
                                                <i class="mdi mdi-currency-usd text-heading mdi-20px"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <h4 class="py-3 mb-2">TICKETERA WEB</h4>

            <p class="mb-4">Añade las entradas a una lista y genera un código QR para que lo validen en Caja.</p>


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

                            <div class="alert alert-solid-danger d-flex align-items-center" style="display:none !important"
                                role="alert" id="income_message">
                                <i class="mdi mdi-alert-circle-outline me-2"></i>
                                Aun no se puede validar por que no es el dia ingresado.</b>
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
                                        <input type="text" id="pruchase_date" name="pruchase_date"
                                            class="form-control" placeholder="john.doe.007" disabled>
                                        <label for="pruchase_date">Fecha de Compra</label>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="form-floating form-floating-outline">
                                        <input type="text" id="admission_date" name="admission_date"
                                            class="form-control" placeholder="john.doe.007" disabled>
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
                                <div class="col-12 col-md-5 btnChangeDNI" style="display: none">
                                    <div class="input-group input-group-merge">
                                        <button type="button" class="btn btn-lg btn-danger" id="btnDNI">
                                            <span class="tf-icons mdi mdi-badge-account-alert-outline me-1"></span>Sol.
                                            Cambio DNI
                                        </button>
                                    </div>
                                </div>

                                <div class="col-12 text-center">
                                    <button type="submit" class="btn btn-primary me-sm-3 me-1 btn_validate"
                                        disabled>Agregar</button>
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
                                <div class="d-flex align-items-start mt-4 align-items-sm-center">
                                    <div
                                        class="d-flex justify-content-between flex-grow-1 align-items-center flex-wrap gap-2">
                                        <button
                                            class="btn btn-danger waves-effect waves-light btn-finish">FINALIZAR</button>
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
                                <button type="submit" class="btn btn-outline-danger me-sm-3 me-1 btn-finish"><i
                                        class="mdi mdi-close-octagon-outline me-1 scaleX-n1-rtl"></i><span
                                        class="align-middle d-none d-sm-inline-block">Finalizar</span></button>
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

            <div class="modal fade" id="modalDNI" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-simple modal-enable-otp modal-dialog-centered">
                    <div class="modal-content p-3 p-md-5">
                        <form id="formChangeDNI" class="row g-3">
                            <input type="hidden" id="appID" name="appID">
                            <input type="hidden" id="appDNIBefore" name="appDNIBefore">
                            <input type="hidden" id="appINames" name="appINames">
                            <div class="modal-body p-md-0">
                                <div class="text-center mb-4">
                                    <h3 class="mb-2 pb-1">Solicitud de Cambio de DNI</h3>
                                </div>
                                <p>Envia una solicitud para rectificar el N° DNI del cliente, siempre y cuando Nombres y
                                    Apellidos del DNI presentado, sean iguales a los datos que se encuentren en la entrada.
                                </p>
                                <ul class="p-0 m-0">
                                    <li class="d-flex mb-4 pb-1">
                                        <div
                                            class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                            <div class="me-2">
                                                <h6 class="mb-0" id="appName"></h6>
                                            </div>
                                            <span class="badge rounded-pill bg-label-success" id="appCodeEntrie"></span>
                                        </div>
                                    </li>
                                </ul>
                                <div class="col-12">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i
                                                class="mdi mdi-card-account-details-outline"></i></span>
                                        <div class="form-floating form-floating-outline">
                                            <input type="text" name="appDNI"
                                                class="form-control phone-number-otp-mask" placeholder="75241414"
                                                id="appDNI" name="appDNI">
                                            <label for="modalEnableOTPPhone">N° DNI</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 d-flex justify-content-center align-items-center">
                                    <div class="input-group">
                                        <div id="overviewChart" class="d-flex align-items-center "></div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-secondary waves-effect"
                                    data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-primary waves-effect waves-light" id="appBtnSend"
                                    disabled>Enviar</button>
                            </div>
                        </form>

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
    <link rel="stylesheet" href="{{ asset('vendor/libs/apex-charts/apex-charts.css') }}">
@endsection()

@section('scripts')
    <script src="{{ asset('vendor/libs/apex-charts/apexcharts.js') }}"></script>

    <script src="{{ asset('js/app-generate-coupon-permission.js') }}"></script>
@endsection
