|@extends('layouts/layout')

@section('content')
    <!-- Content -->
    @php
        $devices = [
            'Tarjeta' => ['name' => 'Tarjeta', 'price' => 4],
            'Portatarjeta' => ['name' => 'Tarjeta + Langer', 'price' => 10],
            'Pulserasilicona' => ['name' => 'Pulsera Silicona', 'price' => 14],
            'Pulserafashion' => ['name' => 'Pulsera Silicona Ajustable', 'price' => 20],
            'Seleccione' => ['name' => 'Sin Dispositivo', 'price' => 0],
        ];

        $shifts = [
            1 => 'Turno Completo',
            2 => 'After School',
        ];

        $sures = [
            0 => ['color' => 'danger', 'label' => 'Inactivo'],
            1 => ['color' => 'success', 'label' => 'Activo'],
        ];
        $status = [
            0 => ['color' => 'danger', 'label' => 'Sin uso'],
            1 => ['color' => 'success', 'label' => 'Usado'],
        ];
    @endphp
    <!-- Content wrapper -->
    <div class="content-wrapper">

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
                                    <h4 class="fw-medium">N° COMPRA # <b id="ticket">{{ $ticket['id'] }}</b></h4>
                                    <div class="mb-1">
                                        <span>Fecha Registro:</span>
                                        <span>
                                            {{ \Carbon\Carbon::parse($ticket['purchase'])->locale('es')->isoFormat('DD [de] MMMM [de] YYYY HH:mm:ss') }}
                                        </span>
                                    </div>
                                    <div class="mb-1">
                                        <span>Código: </span>
                                        <span>{{ $ticket['code'] }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr class="my-0">
                        <div class="card-body">
                            <div class="d-flex justify-content-between flex-wrap">
                                <div class="my-3">
                                    @if ($ticket['type_doc'] === 1)
                                        <h6 class="pb-2">Boletear a:</h6>
                                    @else
                                        <h6 class="pb-2">Facturar a:</h6>
                                    @endif
                                    <table>
                                        <tbody>
                                            <tr>
                                                <td class="pe-3 fw-medium">Comprador:</td>
                                                <td>{{ $ticket['client'] }}</td>
                                            </tr>
                                            @if ($ticket['type_doc'] == 1)
                                                <tr>
                                                    <td class="pe-3 fw-medium">Dni :</td>
                                                    <td>{{ $ticket['dni'] }}</td>
                                                </tr>
                                            @endif

                                            @if ($ticket['type_doc'] == 2)
                                                <tr>
                                                    <td class="pe-3 fw-medium">Razon Social :</td>
                                                    <td>{{ $ticket['rs'] }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="pe-3 fw-medium">RUC :</td>
                                                    <td>{{ $ticket['ruc'] }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="pe-3 fw-medium">Correo Electrónico :</td>
                                                    <td>{{ $ticket['mail'] }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="pe-3 fw-medium">Dirección :</td>
                                                    <td>{{ $ticket['address'] }}</td>
                                                </tr>
                                            @endif

                                        </tbody>
                                    </table>

                                </div>
                            </div>
                        </div>
                        <div class="table-responsive text-nowrap">
                            <table class="table table-borderless m-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>N° Entrada</th>
                                        <th>Beneficiado</th>
                                        <th>Entrada</th>
                                        <th>Horario</th>
                                        <th>Dispositivo</th>
                                        <th>Precio</th>
                                        <th>Fecha Ingreso</th>
                                        <th>Seguro</th>
                                        <th>Estado</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($entries as $entry)
                                        @php
                                            $entrie = $entry['price'] - $devices[$entry['device']]['price'];
                                            $sure = $sures[$entry['sure']];
                                            $use = $status[$entry['status']];
                                        @endphp
                                        <tr>
                                            <td>{{ $entry['id'] }}</td>
                                            <td>{{ $entry['name'] }}</td>
                                            <td>
                                                <p>{{ $entry['entrie'] }}
                                                    <b class="d-flex align-items-center text-success">({{ $entrie }})
                                                    </b>
                                                </p>
                                            </td>
                                            <td>{{ $shifts[$entry['shift']] }}</td>
                                            <td>{{ $devices[$entry['device']]['name'] }}</td>
                                            <td>{{ $entry['price'] }}</td>
                                            <td>{{ date('d/m/Y', strtotime($entry['income'])) }}</td>
                                            <td>
                                                <span class="badge rounded-pill bg-label-{{ $sure['color'] }} me-1">
                                                    {{ $sure['label'] }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge rounded-pill bg-label-{{ $use['color'] }} me-1">
                                                    {{ $use['label'] }}
                                                </span>
                                            </td>

                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-md-0 mb-3">

                                </div>
                                <div class="col-md-6 d-flex justify-content-md-end mt-2">
                                    <div class="invoice-calculations">
                                        <div class="d-flex justify-content-between mb-2">
                                            <span class="w-px-150">Subtotal:</span>
                                            <h6 class="mb-0 pt-1">S/ {{ $ticket['subtotal'] }}</h6>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span class="w-px-150">IGV (18%):</span>
                                            <h6 class="mb-0 pt-1">S/ {{ $ticket['igv'] }}</h6>
                                        </div>
                                        <hr>
                                        <div class="d-flex justify-content-between">
                                            <span class="w-px-150">Total:</span>
                                            <h6 class="mb-0 pt-1">S/ {{ $ticket['total'] }}</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr class="my-0">
                        @if (!$ticket['invoice'])
                            <div class="card-body" id="
                            ">
                                <div class="row">
                                    <div class="col-md-6 mb-md-0 mb-3">
                                        <!-- Contenido aquí -->
                                    </div>
                                    <div class="col-md-6 d-flex justify-content-md-end mt-2">
                                        <div class="invoice-calculations">
                                            <button class="btn btn-primary d-grid w-100 mb-3 waves-effect waves-light"
                                                id="btn-generate">
                                                <span
                                                    class="d-flex align-items-center justify-content-center text-nowrap"><i
                                                        class="mdi mdi-invoice scaleX-n1-rtl me-1"></i>Generado</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                </div>
            </div>
            <div class="content-backdrop fade"></div>

        </div>
        <!-- / Content -->
    @endsection()

    @section('styles')
    @endsection()

    @section('scripts')
        <script src="{{ asset('js/app-invoice.js') }}"></script>
    @endsection
