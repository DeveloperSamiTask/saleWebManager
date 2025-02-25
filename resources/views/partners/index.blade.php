@extends('layouts/layout')

@section('content')
    <!-- Content -->


    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="card-header header-elements">
            </div>
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
                    <div class="col-md-2 d-flex justify-content-end">

                    </div>
                    <div class="col-md-4 d-flex justify-content-end">
                        <div class="card-header-elements ms-auto">
                            <div class="btn-group">
                                <button type="button" class="btn btn-primary waves-effect waves-light" id="editPartner"><i
                                        class="mdi mdi-account-search mdi-20px"></i> &nbsp;Editar
                                    Socio</button>
                            </div>
                        </div>
                        <div class="card-header-elements ms-auto">
                            <div class="btn-group">
                                <button type="button" class="btn btn-primary waves-effect waves-light"
                                    id="searchPartner"><i class="mdi mdi-credit-card-sync mdi-20px"></i> &nbsp;Renovar
                                    Socio</button>
                            </div>
                        </div>
                    </div>
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
        <div class="modal-dialog modal-xl modal-simple modal-dialog-centered">
            <div class="modal-content p-3 p-md-5">
                <div class="modal-body p-md-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="text-center mb-4">
                        <h3 class="address-title mb-2 pb-1">Ficha Inscripción</h3>
                    </div>
                    <form id="partnerForm" class="row g-4" onsubmit="return false">
                        <h4>Datos del Socio</h4>

                        <div class="col-3">
                            <div class="form-floating form-floating-outline">
                                <input type="text" id="code" name="code" class="form-control"
                                    placeholder="Código" disabled>
                                <label for="code">Código</label>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-floating form-floating-outline">
                                <input type="text" id="pattername" name="pattername" class="form-control"
                                    placeholder="Apellido Paterno">
                                <label for="pattername">Apellido Paterno</label>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-floating form-floating-outline">
                                <input type="text" id="mattername" name="mattername" class="form-control"
                                    placeholder="Apellido Materno">
                                <label for="matternamea">Apellido Materno</label>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-floating form-floating-outline">
                                <input type="text" id="names" name="names" class="form-control"
                                    placeholder="Nombres">
                                <label for="names">Nombres</label>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-floating form-floating-outline">
                                <input type="text" id="doc" name="doc" class="form-control"
                                    placeholder="Número de Documento">
                                <label for="doc">Número Documento</label>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-floating form-floating-outline">
                                <input type="text" class="form-control flatpickr-date" placeholder="YYYY-MM-DD"
                                    name="birthdate">
                                <label for="birthdate">Fecha de Nacimiento</label>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-floating form-floating-outline">
                                <input type="text" id="affiliation" name="affiliation" class="form-control"
                                    placeholder="Ficha Afilicación">
                                <label for="affiliation">Ficha Afilicación</label>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-floating form-floating-outline">
                                <input type="text" class="form-control flatpickr-date" placeholder="YYYY-MM-DD"
                                    name="initdate" id="initdate">
                                <label for="initdate">Inicio Socio</label>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-floating form-floating-outline">
                                <input type="text" class="form-control flatpickr-date" placeholder="YYYY-MM-DD"
                                    name="enddate" id="enddate">
                                <label for="enddate">Vencimiento</label>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-floating form-floating-outline">
                                <input type="text" id="address" name="address" class="form-control"
                                    placeholder="Dirección">
                                <label for="address">Dirección</label>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-floating form-floating-outline">
                                <input type="text" id="phone" name="phone" class="form-control"
                                    placeholder="Número Celular">
                                <label for="phone">Número Celular</label>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-floating form-floating-outline">
                                <input type="text" id="mail" name="mail" class="form-control"
                                    placeholder="E-mail">
                                <label for="mail">E-mail</label>
                            </div>
                        </div>
                        <div class="accordion mt-3" id="accordionExample">
                            <div class="accordion-item">
                                <h4 class="accordion-header" id="headingOne">
                                    <button type="button" class="accordion-button collapsed" data-bs-toggle="collapse"
                                        data-bs-target="#accordionOne" aria-expanded="false"
                                        aria-controls="accordionOne">
                                        Datos del Apoderado o Representante
                                    </button>
                                </h4>
                                <div id="accordionOne" class="accordion-collapse collapse"
                                    data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        <div class="row">
                                            <div class="col-3">
                                                <div class="form-floating form-floating-outline">
                                                    <input type="text" id="proxyPatter" name="proxyPatter"
                                                        class="form-control" placeholder="Apellido Paterno">
                                                    <label for="proxyPatter">Apellido Paterno</label>
                                                </div>
                                            </div>
                                            <div class="col-3">
                                                <div class="form-floating form-floating-outline">
                                                    <input type="text" id="proxyMatter" name="proxyMatter"
                                                        class="form-control" placeholder="Apellido Materno">
                                                    <label for="proxyMatter">Apellido Materno</label>
                                                </div>
                                            </div>
                                            <div class="col-3">
                                                <div class="form-floating form-floating-outline">
                                                    <input type="text" id="proxyNames" name="proxyNames"
                                                        class="form-control" placeholder="Nombres">
                                                    <label for="proxyNames">Nombres</label>
                                                </div>
                                            </div>
                                            <div class="col-3">
                                                <div class="form-floating form-floating-outline">
                                                    <input type="text" id="proxyDoc" name="proxyDoc"
                                                        class="form-control" placeholder="Nombres">
                                                    <label for="proxyDoc">Nº Doc</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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


    <div class="modal fade" id="renew-modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-simple modal-dialog-centered">
            <div class="modal-content p-3 p-md-5">
                <div class="modal-body p-md-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="text-center mb-4">
                        <h3 class="address-title mb-2 pb-1">Renovar Socio</h3>
                    </div>

                    <form id="renewForm" class="row g-4" onsubmit="return false">

                        <div class="row g-3 mb-3">
                            <div class="col-sm-4 col-xxl-4 col-xl-12">
                                <input type="text" class="form-control" placeholder="Buscar por DNI"
                                    id="searchInput">
                            </div>
                            <div class="col-sm-4 col-xxl-4 col-xl-12">
                                <select class="form-select" name="" id="selectSearch">
                                    <option value="charClienteDni">DNI</option>
                                    <option value="cClieCode">Código</option>
                                </select>
                            </div>
                            <div class="col-4 col-xxl-4 col-xl-12">
                                <div class="d-grid">
                                    <button type="button" class="btn btn-outline-primary waves-effect"
                                        id="btnSearch">Buscar</button>
                                </div>
                            </div>
                        </div>

                        <h4>Datos del Socio</h4>
                        <input type="hidden" id="hiddenCode" name="hiddenCode" class="form-control">

                        <div class="col-4">
                            <div class="form-floating form-floating-outline">
                                <input type="text" id="codeRenew" name="codeRenew" class="form-control"
                                    placeholder="Código" disabled>
                                <label for="codeRenew">Código</label>
                            </div>
                        </div>
                        <div class="col-8">
                            <div class="form-floating form-floating-outline">
                                <input type="text" id="namesRenew" name="namesRenew" class="form-control"
                                    placeholder="Nombres" disabled>
                                <label for="namesRenew">Socio</label>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-floating form-floating-outline">
                                <input type="text" id="docRenew" name="docRenew" class="form-control"
                                    placeholder="Número de Documento" disabled>
                                <label for="docRenew">Número Documento</label>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-floating form-floating-outline">
                                <input type="text" class="form-control flatpickr-date" placeholder="YYYY-MM-DD"
                                    name="birthdateRenew" id="birthdateRenew" disabled>
                                <label for="birthdate">Fecha de Nacimiento</label>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-floating form-floating-outline">
                                <input type="text" id="renewAffiliation" name="renewAffiliation" class="form-control"
                                    placeholder="Ficha Renovación">
                                <label for="affiliation">Ficha Renovación</label>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-floating form-floating-outline">
                                <input type="text" class="form-control flatpickr-date" placeholder="YYYY-MM-DD"
                                    name="renewInitdate" id="renewInitdate">
                                <label for="renewInitdate">Renovación Socio</label>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-floating form-floating-outline">
                                <input type="text" class="form-control flatpickr-date" placeholder="YYYY-MM-DD"
                                    name="renewEnddate" id="renewEnddate">
                                <label for="renewEnddate">Vencimiento</label>
                            </div>
                        </div>
                        <div class="col-12 text-center">
                            <button type="submit" class="btn btn-primary me-sm-3 me-1 btnRenew" disabled>Renovar</button>
                            <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal"
                                aria-label="Close">Cancelar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="edit-modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-simple modal-dialog-centered">
            <div class="modal-content p-3 p-md-5">
                <div class="modal-body p-md-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="text-center mb-4">
                        <h3 class="address-title mb-2 pb-1">Editar Socio</h3>
                    </div>
                    <form id="editForm" class="row g-4" onsubmit="return false">

                        <div class="row g-3 mb-3">
                            <div class="col-sm-4 col-xxl-4 col-xl-12">
                                <input type="text" class="form-control" placeholder="Buscar por DNI"
                                    id="inputSelect">
                            </div>
                            <div class="col-sm-4 col-xxl-4 col-xl-12">
                                <select class="form-select" name="" id="editSelect">
                                    <option value="charClienteDni">DNI</option>
                                    <option value="cClieCode">Código</option>
                                </select>
                            </div>
                            <div class="col-4 col-xxl-4 col-xl-12">
                                <div class="d-grid">
                                    <button type="button" class="btn btn-outline-primary waves-effect"
                                        id="editBtn">Buscar</button>
                                </div>
                            </div>
                        </div>

                        <h4>Datos del Socio</h4>
                        <input type="hidden" id="editCodeHidden" name="editCodeHidden" class="form-control">
                        <div class="col-3">
                            <div class="form-floating form-floating-outline">
                                <input type="text" id="editcode" name="editcode" class="form-control"
                                    placeholder="Código" disabled>
                                <label for="code">Código</label>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-floating form-floating-outline">
                                <input type="text" id="editpattername" name="editpattername" class="form-control"
                                    placeholder="Apellido Paterno">
                                <label for="pattername">Apellido Paterno</label>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-floating form-floating-outline">
                                <input type="text" id="editmattername" name="editmattername" class="form-control"
                                    placeholder="Apellido Materno">
                                <label for="matternamea">Apellido Materno</label>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-floating form-floating-outline">
                                <input type="text" id="editnames" name="editnames" class="form-control"
                                    placeholder="Nombres">
                                <label for="names">Nombres</label>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-floating form-floating-outline">
                                <input type="text" id="editdoc" name="editdoc" class="form-control"
                                    placeholder="Número de Documento">
                                <label for="doc">Número Documento</label>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-floating form-floating-outline">
                                <input type="text" class="form-control flatpickr-date" placeholder="YYYY-MM-DD"
                                    name="editbirthdate" id="editbirthdate">
                                <label for="birthdate">Fecha de Nacimiento</label>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-floating form-floating-outline">
                                <input type="text" id="editaffiliation" name="editaffiliation" class="form-control"
                                    placeholder="Ficha Afilicación">
                                <label for="editaffiliation">Ficha Afilicación</label>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-floating form-floating-outline">
                                <input type="text" class="form-control flatpickr-date" placeholder="YYYY-MM-DD"
                                    name="editinitdate" id="editinitdate">
                                <label for="editinitdate">Inicio Socio</label>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-floating form-floating-outline">
                                <input type="text" class="form-control flatpickr-date" placeholder="YYYY-MM-DD"
                                    name="editenddate" id="editenddate">
                                <label for="editenddate">Vencimiento</label>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-floating form-floating-outline">
                                <input type="text" id="editaddress" name="editaddress" class="form-control"
                                    placeholder="Dirección">
                                <label for="editaddress">Dirección</label>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-floating form-floating-outline">
                                <input type="text" id="editphone" name="editphone" class="form-control"
                                    placeholder="Número Celular">
                                <label for="editphone">Número Celular</label>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-floating form-floating-outline">
                                <input type="text" id="editmail" name="editmail" class="form-control"
                                    placeholder="E-mail">
                                <label for="editmail">E-mail</label>
                            </div>
                        </div>
                        <div class="accordion mt-3" id="editaccordionExample">
                            <div class="accordion-item">
                                <h4 class="accordion-header" id="editheadingOne">
                                    <button type="button" class="accordion-button collapsed" data-bs-toggle="collapse"
                                        data-bs-target="#EditaccordionOne" aria-expanded="false"
                                        aria-controls="accordionOne">
                                        Datos del Apoderado o Representante
                                    </button>
                                </h4>
                                <div id="EditaccordionOne" class="accordion-collapse collapse"
                                    data-bs-parent="#editaccordionExample">
                                    <div class="accordion-body">
                                        <div class="row">
                                            <div class="col-3">
                                                <div class="form-floating form-floating-outline">
                                                    <input type="text" id="editproxyPatter" name="editproxyPatter"
                                                        class="form-control" placeholder="Apellido Paterno">
                                                    <label for="editproxyPatter">Apellido Paterno</label>
                                                </div>
                                            </div>
                                            <div class="col-3">
                                                <div class="form-floating form-floating-outline">
                                                    <input type="text" id="editproxyMatter" name="editproxyMatter"
                                                        class="form-control" placeholder="Apellido Materno">
                                                    <label for="editproxyMatter">Apellido Materno</label>
                                                </div>
                                            </div>
                                            <div class="col-3">
                                                <div class="form-floating form-floating-outline">
                                                    <input type="text" id="editproxyNames" name="editproxyNames"
                                                        class="form-control" placeholder="Nombres">
                                                    <label for="editproxyNames">Nombres</label>
                                                </div>
                                            </div>
                                            <div class="col-3">
                                                <div class="form-floating form-floating-outline">
                                                    <input type="text" id="editproxyDoc" name="editproxyDoc"
                                                        class="form-control" placeholder="Nombres">
                                                    <label for="editproxyDoc">Nº Doc</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 text-center">
                            <button type="submit" class="btn btn-primary me-sm-3 me-1">Editar</button>
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
