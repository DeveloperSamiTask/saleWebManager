@extends('layouts/layout')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">

        <h4 class="py-3 mb-4">
            <span class="text-muted fw-light">Configuración /</span> Plantillas
        </h4>

        <div class="card mb-4">
            <div class="card-header border-bottom">
                <h5 class="card-title">Lista de plantillas</h5>
            </div>
            <div class="card-datatable table-responsive">
                <table class="datatables-categories table">
                    <thead class="table-light">
                        <tr>
                            <th></th>
                            <th>Paquete Empresa</th>
                            <th>Plantilla Empresa</th>
                            <th>Promocion</th>
                            <th></th>
                        </tr>
                    </thead>
                </table>
            </div>

            <div class="modal fade" id="addNewTemplate" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-simple modal-dialog-centered">
                    <div class="modal-content p-3 p-md-5">
                        <div class="modal-body p-md-0">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            <div class="text-center mb-4">
                                <h3 class="modal-title mb-2 pb-1"></h3>
                            </div>
                            <form id="formTemplate" class="row g-4" onsubmit="return false" enctype="multipart/form-data">
                                <input type="hidden" name="template_id" id="template_id">
                                <div class="col-12">
                                    <div class="form-floating form-floating-outline">
                                        <select id="template_company" name="template_company" class="select2 form-select"
                                            required data-placeholder="Selecciona la Empresa del Template"
                                            data-allow-clear="true">
                                            <option value=""></option>
                                            @foreach ($companies as $company)
                                                <option value="{{ $company->id }}">{{ $company->name }}</option>
                                            @endforeach
                                        </select>
                                        <label for="template_company">Empresa del Template <span
                                                class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class="col-5">
                                    <div class="form-floating form-floating-outline">
                                        <select id="company" name="company" class="select2 form-select"
                                            data-placeholder="Selecciona Empresa" data-allow-clear="true">
                                            <option value=""></option>
                                            @foreach ($companies as $company)
                                                <option value="{{ $company->id }}">{{ $company->name }}</option>
                                            @endforeach
                                        </select>
                                        <label for="company">Empresa</label>
                                    </div>
                                </div>
                                <div class="col-7">
                                    <div class="form-floating form-floating-outline">
                                        <select id="promotion" name="promotion" class="select2 form-select"
                                            data-placeholder="Selecciona Promoción" data-allow-clear="true">
                                        </select>
                                        <label for="promotion">Promociones</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div id="full-editor">
                                    </div>
                                </div>
                                <div class="col-12 text-center">
                                    <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit">Agregar</button>
                                    <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal"
                                        aria-label="Close">Cancelar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('vendor/libs/quill/katex.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/libs/quill/editor.css') }}">
@endsection

@section('scripts')
    <script src="{{ asset('vendor/libs/quill/katex.js') }}"></script>
    <script src="{{ asset('vendor/libs/quill/quill.js') }}"></script>

    <script src="{{ asset('js/templates.js') }}"></script>
@endsection
