@extends('layouts/layout')

@section('content')
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
                <table class="datatables-promotions table">
                    <thead class="table-light">
                        <tr>
                            <th></th>
                            <th>PROMOCION</th>
                            <th>CANTIDAD</th>
                            <th>PRECIO</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <!-- / Content -->
    <!-- Add New Address Modal -->
    <div class="modal fade" id="addNewPromotion" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-simple modal-dialog-centered">
            <div class="modal-content p-3 p-md-5">
                <div class="modal-body p-md-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="text-center mb-4">
                        <h3 class="address-title mb-2 pb-1">Agregar Promocion</h3>
                    </div>
                    <form id="addNewPromotionForm" class="row g-4" onsubmit="return false">
                        <div class="col-12 col-md-6">
                            <div class="form-floating form-floating-outline">
                                <input type="text" id="promo" name="promo" class="form-control"
                                    placeholder="Ingresar nombre">
                                <label for="promo">Nombre Promoción</label>
                            </div>
                        </div>
                        <div class="col-12 col-md-3">
                            <div class="form-floating form-floating-outline">
                                <input type="text" id="price" name="price" class="form-control"
                                    placeholder="Ingresar precio" value="0">
                                <label for="price">Precio</label>
                            </div>
                        </div>
                        <div class="col-12 col-md-3">
                            <div class="form-floating form-floating-outline">
                                <input type="text" id="members" name="members" class="form-control"
                                    placeholder="Ingresar cantidad">
                                <label for="members">Cantidad</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-floating form-floating-outline">
                                <input type="text" id="description" name="description" class="form-control"
                                    placeholder="Ingresar la descripción">
                                <label for="description">Descripción Promoción</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="switch switch-lg">
                                <input type="checkbox" class="switch-input" name="has_food" value="1">
                                <span class="switch-toggle-slider">
                                    <span class="switch-on"></span>
                                    <span class="switch-off"></span>
                                </span>
                                <span class="switch-label">Activar si la promoción contiene alimentos.</span>
                            </label>
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
    <script src="{{ asset('js/bowling/app-list-promotions_courtesy.js') }}"></script>
@endsection
