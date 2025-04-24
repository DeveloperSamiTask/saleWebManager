@extends('layouts/layout')

@section('content')
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">

        <div class="card">
            <div class="card-header border-bottom">
                <h5 class="card-title">Filtro de búsqueda</h5>
                <div class="d-flex justify-content-between align-items-center row py-3 gap-3 gap-md-0">
                    <div class="col-md-3"></div>
                    <div class="col-md-3"></div>
                    <div class="col-md-3 user_role"></div>
                    <div class="col-md-3 user_plan"></div>
                </div>
            </div>
            <div class="card-datatable table-responsive">
                <table class="datatables-entries table">
                    <thead class="table-light">
                        <tr>
                            <th></th>
                            <th>Nombre</th>
                            <th>Rol</th>
                            <th>Empresas</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <!-- / Content -->

    <!-- offcanvas -->

    <!-- Offcanvas to add new user -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasUser" aria-labelledby="offcanvasAddUserLabel">
        <div class="offcanvas-header">
            <h5 id="offcanvasAddUserLabel" class="offcanvas-title">

            </h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body mx-0 flex-grow-0 h-100">
            <form class="add-new-user pt-0" id="formUser" onsubmit="return false">

                <input type="hidden" id="user_id" name="user_id" value="" />

                <div class="form-floating form-floating-outline mb-4">
                    <input type="text" class="form-control" id="user_name" placeholder="Nombre de Usuario"
                        name="user_name" aria-label="Nombre de Usuario" />
                    <label for="user_name">Nombre Usuario</label>
                </div>
                <div class="form-floating form-floating-outline mb-4">
                    <input type="text" id="user_password" class="form-control" placeholder="Contraseña"
                        aria-label="Contraseña" name="user_password" />
                    <label for="user_password">Contraseña</label>
                </div>
                <div class="form-floating form-floating-outline mb-4">
                    <select id="user_role" name="user_role" class="select2 form-select" data-placeholder="Seleccionar rol">
                        <option value=""></option>
                        @foreach ($roles as $rol)
                            <option value="{{ $rol->idrol }}">{{ $rol->rol }}</option>
                        @endforeach
                    </select>
                    <label for="user_role">Rol</label>
                </div>
                <div class="form-floating form-floating-outline mb-4">
                    <select name="user_companies[]" id="user_companies" class="select2 form-select"
                        data-placeholder="Seleccionar empresas" multiple>
                        @foreach ($companies as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                    <label for="user_companies">Empresa</label>
                </div>
                <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit">
                    Guardar
                </button>
                <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="offcanvas">
                    Cancelar
                </button>
            </form>
        </div>
    </div>
@endsection()

@section('styles')
    <link rel="stylesheet" href="{{ asset('vendor/libs/flatpickr/flatpickr.css') }}">
@endsection()

@section('scripts')
    <script src="{{ asset('vendor/libs/flatpickr/flatpickr.js') }}"></script>
    <script src="{{ asset('js/users.js') }}"></script>
@endsection
