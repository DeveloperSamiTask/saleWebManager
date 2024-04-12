@extends('layouts/layout')

@section('content')
    <!-- Content -->
    <div class="content-wrapper">

        <!-- Content -->

        <div class="container-xxl flex-grow-1 container-p-y">


            <h4 class="py-3 mb-4">
                <span class="text-muted fw-light">Logistics /</span> Dashboard
            </h4>

            <!-- Card Border Shadow -->
            <div class="row">
                <div class="col-sm-6 col-lg-3 mb-4">
                    <div class="card card-border-shadow-primary h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-2 pb-1">
                                <div class="avatar me-2">
                                    <span class="avatar-initial rounded bg-label-primary"><i
                                            class="mdi mdi-bus-school mdi-20px"></i></span>
                                </div>
                                <h4 class="ms-1 mb-0 display-6">42</h4>
                            </div>
                            <p class="mb-0 text-heading">On route vehicles</p>
                            <p class="mb-0">
                                <span class="me-1">+18.2%</span>
                                <small class="text-muted">than last week</small>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3 mb-4">
                    <div class="card card-border-shadow-warning h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-2 pb-1">
                                <div class="avatar me-2">
                                    <span class="avatar-initial rounded bg-label-warning">
                                        <i class='mdi mdi-alert mdi-20px'></i></span>
                                </div>
                                <h4 class="ms-1 mb-0 display-6">8</h4>
                            </div>
                            <p class="mb-0 text-heading">Vehicles with errors</p>
                            <p class="mb-0">
                                <span class="me-1">-8.7%</span>
                                <small class="text-muted">than last week</small>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3 mb-4">
                    <div class="card card-border-shadow-danger h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-2 pb-1">
                                <div class="avatar me-2">
                                    <span class="avatar-initial rounded bg-label-danger">
                                        <i class='mdi mdi-source-fork mdi-20px'></i>
                                    </span>
                                </div>
                                <h4 class="ms-1 mb-0 display-6">27</h4>
                            </div>
                            <p class="mb-0 text-heading">Deviated from route</p>
                            <p class="mb-0">
                                <span class="me-1">+4.3%</span>
                                <small class="text-muted">than last week</small>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3 mb-4">
                    <div class="card card-border-shadow-info h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-2 pb-1">
                                <div class="avatar me-2">
                                    <span class="avatar-initial rounded bg-label-info"><i
                                            class='mdi mdi-timer-outline mdi-20px'></i></span>
                                </div>
                                <h4 class="ms-1 mb-0 display-6">13</h4>
                            </div>
                            <p class="mb-0 text-heading">Late vehicles</p>
                            <p class="mb-0">
                                <span class="me-1">-2.5%</span>
                                <small class="text-muted">than last week</small>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <!--/ Card Border Shadow -->
            <div class="row">
                <!-- Shipment statistics-->
                <div class="col-lg-12 col-xxl-12 mb-4 order-3 order-xxl-1">
                    <div class="card">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <div class="card-title mb-0">
                                <h5 class="m-0 me-2 mb-1">Entradas Compradas</h5>
                            </div>
                            <div class="col-md-3">
                                <div class="form-floating form-floating-outline">
                                    <input type="text" class="form-control" placeholder="YYYY-MM-DD to YYYY-MM-DD"
                                        id="flatpickr-range">
                                    <label for="flatpickr-range">Rango de Fechas</label>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="shipmentStatisticsChart"></div>
                        </div>
                    </div>
                </div>
                <!--/ Shipment statistics -->
                <!-- Delivery Performance -->
                <div class="col-lg-6 col-xxl-4 mb-4 order-2 order-xxl-2">
                    <div class="card h-100">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <div class="card-title mb-0">
                                <h5 class="m-0 me-2 mb-1">Delivery Performance</h5>
                                <p class="text-body mb-0">12% increase in this month</p>
                            </div>
                            <div class="dropdown">
                                <button class="btn p-0" type="button" id="deliveryPerformance" data-bs-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    <i class="mdi mdi-dots-vertical mdi-24px"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="deliveryPerformance">
                                    <a class="dropdown-item" href="javascript:void(0);">Select All</a>
                                    <a class="dropdown-item" href="javascript:void(0);">Refresh</a>
                                    <a class="dropdown-item" href="javascript:void(0);">Share</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <ul class="p-0 m-0">
                                <li class="d-flex mb-4 pb-1">
                                    <div class="avatar flex-shrink-0 me-3">
                                        <span class="avatar-initial rounded bg-label-primary"><i
                                                class="mdi mdi-wallet-giftcard mdi-24px"></i></span>
                                    </div>
                                    <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                        <div class="me-2">
                                            <h6 class="mb-1 fw-normal">Packages in transit</h6>
                                            <small class="text-success fw-normal d-block">
                                                <i class="mdi mdi-chevron-up"></i>
                                                25.8%
                                            </small>
                                        </div>
                                        <div class="user-progress">
                                            <h6 class="mb-0">10k</h6>
                                        </div>
                                    </div>
                                </li>
                                <li class="d-flex mb-4 pb-1">
                                    <div class="avatar flex-shrink-0 me-3">
                                        <span class="avatar-initial rounded bg-label-info"><i
                                                class="mdi mdi-bus-school mdi-24px"></i></span>
                                    </div>
                                    <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                        <div class="me-2">
                                            <h6 class="mb-1 fw-normal">Packages out for delivery</h6>
                                            <small class="text-success fw-normal d-block">
                                                <i class="mdi mdi-chevron-up"></i>
                                                4.3%
                                            </small>
                                        </div>
                                        <div class="user-progress">
                                            <h6 class="mb-0">5k</h6>
                                        </div>
                                    </div>
                                </li>
                                <li class="d-flex mb-4 pb-1">
                                    <div class="avatar flex-shrink-0 me-3">
                                        <span class="avatar-initial rounded bg-label-success"><i
                                                class="mdi mdi-check text-success mdi-24px"></i></span>
                                    </div>
                                    <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                        <div class="me-2">
                                            <h6 class="mb-1 fw-normal">Packages delivered</h6>
                                            <small class="text-danger fw-normal d-block">
                                                <i class="mdi mdi-chevron-down"></i>
                                                12.5
                                            </small>
                                        </div>
                                        <div class="user-progress">
                                            <h6 class="mb-0">15k</h6>
                                        </div>
                                    </div>
                                </li>
                                <li class="d-flex mb-4 pb-1">
                                    <div class="avatar flex-shrink-0 me-3">
                                        <span class="avatar-initial rounded bg-label-warning"><i
                                                class="mdi mdi-home-outline mdi-24px"></i></span>
                                    </div>
                                    <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                        <div class="me-2">
                                            <h6 class="mb-1 fw-normal">Delivery success rate</h6>
                                            <small class="text-success fw-normal d-block">
                                                <i class="mdi mdi-chevron-up"></i>
                                                35.6%
                                            </small>
                                        </div>
                                        <div class="user-progress">
                                            <h6 class="mb-0">95%</h6>
                                        </div>
                                    </div>
                                </li>
                                <li class="d-flex mb-4 pb-1">
                                    <div class="avatar flex-shrink-0 me-3">
                                        <span class="avatar-initial rounded bg-label-secondary"><i
                                                class="mdi mdi-timer-outline mdi-24px"></i></span>
                                    </div>
                                    <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                        <div class="me-2">
                                            <h6 class="mb-1 fw-normal">Average delivery time</h6>
                                            <small class="text-danger fw-normal d-block">
                                                <i class="mdi mdi-chevron-down"></i>
                                                2.15
                                            </small>
                                        </div>
                                        <div class="user-progress">
                                            <h6 class="mb-0">2.5 Days</h6>
                                        </div>
                                    </div>
                                </li>
                                <li class="d-flex">
                                    <div class="avatar flex-shrink-0 me-3">
                                        <span class="avatar-initial rounded bg-label-danger"><i
                                                class="mdi mdi-account-outline mdi-24px"></i></span>
                                    </div>
                                    <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                        <div class="me-2">
                                            <h6 class="mb-1 fw-normal">Customer satisfaction</h6>
                                            <small class="text-success fw-normal d-block">
                                                <i class="mdi mdi-chevron-up"></i>
                                                5.7%
                                            </small>
                                        </div>
                                        <div class="user-progress">
                                            <h6 class="mb-0">4.5/5</h6>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!--/ Delivery Performance -->
                <!-- Reasons for delivery exceptions -->
                <div class="col-md-6 col-xxl-4 mb-4 order-1 order-xxl-3">
                    <div class="card h-100">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <div class="card-title mb-0">
                                <h5 class="m-0 me-2">Reasons for delivery exceptions</h5>
                            </div>
                            <div class="dropdown">
                                <button class="btn p-0" type="button" id="deliveryExceptions" data-bs-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    <i class="mdi mdi-dots-vertical mdi-24px"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="deliveryExceptions">
                                    <a class="dropdown-item" href="javascript:void(0);">Select All</a>
                                    <a class="dropdown-item" href="javascript:void(0);">Refresh</a>
                                    <a class="dropdown-item" href="javascript:void(0);">Share</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="deliveryExceptionsChart"></div>
                        </div>
                    </div>
                </div>
                <!--/ Reasons for delivery exceptions -->
                <!-- Orders by Countries -->
                <div class="col-md-6 col-xxl-4 mb-4 order-0 order-xxl-4">
                    <div class="card h-100">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <div class="card-title mb-0">
                                <h5 class="m-0 me-2 mb-1">Orders by Countries</h5>
                                <p class="text-body mb-0">62 deliveries in progress</p>
                            </div>
                            <div class="dropdown">
                                <button class="btn p-0" type="button" id="ordersCountries" data-bs-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    <i class="mdi mdi-dots-vertical mdi-24px"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="ordersCountries">
                                    <a class="dropdown-item" href="javascript:void(0);">Select All</a>
                                    <a class="dropdown-item" href="javascript:void(0);">Refresh</a>
                                    <a class="dropdown-item" href="javascript:void(0);">Share</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="nav-align-top">
                                <ul class="nav nav-tabs nav-fill tabs-line border-bottom-0" role="tablist">
                                    <li class="nav-item">
                                        <button type="button" class="nav-link active" role="tab"
                                            data-bs-toggle="tab" data-bs-target="#navs-justified-new"
                                            aria-controls="navs-justified-new" aria-selected="true">New</button>
                                    </li>
                                    <li class="nav-item">
                                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                                            data-bs-target="#navs-justified-link-preparing"
                                            aria-controls="navs-justified-link-preparing"
                                            aria-selected="false">Preparing</button>
                                    </li>
                                    <li class="nav-item">
                                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                                            data-bs-target="#navs-justified-link-shipping"
                                            aria-controls="navs-justified-link-shipping"
                                            aria-selected="false">Shipping</button>
                                    </li>
                                </ul>
                                <div class="tab-content border-0 pb-0 px-4 mx-1">
                                    <div class="tab-pane fade show active" id="navs-justified-new" role="tabpanel">
                                        <ul class="timeline mb-0">
                                            <li class="timeline-item ps-4 border-left-dashed">
                                                <span
                                                    class="timeline-indicator-advanced text-success border-0 shadow-none">
                                                    <i class='mdi mdi-check-circle-outline'></i>
                                                </span>
                                                <div class="timeline-event ps-1">
                                                    <div class="timeline-header">
                                                        <small class="text-success text-uppercase fw-medium">sender</small>
                                                    </div>
                                                    <h6 class="mb-2">Myrtle Ullrich</h6>
                                                    <p class="mb-0">101 Boulder, California(CA), 95959</p>
                                                </div>
                                            </li>
                                            <li class="timeline-item ps-4 border-transparent">
                                                <span
                                                    class="timeline-indicator-advanced text-primary border-0 shadow-none">
                                                    <i class='mdi mdi-map-marker-outline'></i>
                                                </span>
                                                <div class="timeline-event ps-1">
                                                    <div class="timeline-header">
                                                        <small
                                                            class="text-primary text-uppercase fw-medium">Receiver</small>
                                                    </div>
                                                    <h6 class="mb-2">Barry Schowalter</h6>
                                                    <p class="mb-0">939 Orange, California(CA), 92118</p>
                                                </div>
                                            </li>
                                        </ul>
                                        <div class="border-1 border-light border-top border-dashed mb-2"></div>
                                        <ul class="timeline mb-0">
                                            <li class="timeline-item ps-4 border-left-dashed">
                                                <span
                                                    class="timeline-indicator-advanced text-success border-0 shadow-none">
                                                    <i class='mdi mdi-check-circle-outline'></i>
                                                </span>
                                                <div class="timeline-event ps-1">
                                                    <div class="timeline-header">
                                                        <small class="text-success text-uppercase fw-medium">sender</small>
                                                    </div>
                                                    <h6 class="mb-2">Veronica Herman</h6>
                                                    <p class="mb-0">162 Windsor, California(CA), 95492</p>
                                                </div>
                                            </li>
                                            <li class="timeline-item ps-4 border-transparent">
                                                <span
                                                    class="timeline-indicator-advanced text-primary border-0 shadow-none">
                                                    <i class='mdi mdi-map-marker-outline'></i>
                                                </span>
                                                <div class="timeline-event ps-1">
                                                    <div class="timeline-header">
                                                        <small
                                                            class="text-primary text-uppercase fw-medium">Receiver</small>
                                                    </div>
                                                    <h6 class="mb-2">Helen Jacobs</h6>
                                                    <p class="mb-0">487 Sunset, California(CA), 94043</p>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="tab-pane fade" id="navs-justified-link-preparing" role="tabpanel">
                                        <ul class="timeline mb-0">
                                            <li class="timeline-item ps-4 border-left-dashed">
                                                <span
                                                    class="timeline-indicator-advanced text-success border-0 shadow-none">
                                                    <i class='mdi mdi-check-circle-outline'></i>
                                                </span>
                                                <div class="timeline-event ps-1">
                                                    <div class="timeline-header">
                                                        <small class="text-success text-uppercase fw-medium">sender</small>
                                                    </div>
                                                    <h6 class="mb-2">Barry Schowalter</h6>
                                                    <p class="mb-0">939 Orange, California(CA), 92118</p>
                                                </div>
                                            </li>
                                            <li class="timeline-item ps-4 border-transparent border-left-dashed">
                                                <span
                                                    class="timeline-indicator-advanced text-primary border-0 shadow-none">
                                                    <i class='mdi mdi-map-marker-outline'></i>
                                                </span>
                                                <div class="timeline-event ps-1">
                                                    <div class="timeline-header">
                                                        <small
                                                            class="text-primary text-uppercase fw-medium">Receiver</small>
                                                    </div>
                                                    <h6 class="mb-2">Myrtle Ullrich</h6>
                                                    <p class="mb-0">101 Boulder, California(CA), 95959 </p>
                                                </div>
                                            </li>
                                        </ul>
                                        <div class="border-1 border-light border-top border-dashed mb-2 "></div>
                                        <ul class="timeline mb-0">
                                            <li class="timeline-item ps-4 border-left-dashed">
                                                <span
                                                    class="timeline-indicator-advanced text-success border-0 shadow-none">
                                                    <i class='mdi mdi-check-circle-outline'></i>
                                                </span>
                                                <div class="timeline-event ps-1">
                                                    <div class="timeline-header">
                                                        <small class="text-success text-uppercase fw-medium">sender</small>
                                                    </div>
                                                    <h6 class="mb-2">Veronica Herman</h6>
                                                    <p class="mb-0">162 Windsor, California(CA), 95492</p>
                                                </div>
                                            </li>
                                            <li class="timeline-item ps-4 border-transparent">
                                                <span
                                                    class="timeline-indicator-advanced text-primary border-0 shadow-none">
                                                    <i class='mdi mdi-map-marker-outline'></i>
                                                </span>
                                                <div class="timeline-event ps-1">
                                                    <div class="timeline-header">
                                                        <small
                                                            class="text-primary text-uppercase fw-medium">Receiver</small>
                                                    </div>
                                                    <h6 class="mb-2">Helen Jacobs</h6>
                                                    <p class="mb-0">487 Sunset, California(CA), 94043</p>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="tab-pane fade" id="navs-justified-link-shipping" role="tabpanel">
                                        <ul class="timeline mb-0">
                                            <li class="timeline-item ps-4 border-left-dashed">
                                                <span
                                                    class="timeline-indicator-advanced text-success border-0 shadow-none">
                                                    <i class='mdi mdi-check-circle-outline'></i>
                                                </span>
                                                <div class="timeline-event ps-1">
                                                    <div class="timeline-header">
                                                        <small class="text-success text-uppercase fw-medium">sender</small>
                                                    </div>
                                                    <h6 class="mb-2">Veronica Herman</h6>
                                                    <p class="mb-0">101 Boulder, California(CA), 95959</p>
                                                </div>
                                            </li>
                                            <li class="timeline-item ps-4 border-transparent">
                                                <span
                                                    class="timeline-indicator-advanced text-primary border-0 shadow-none">
                                                    <i class='mdi mdi-map-marker-outline'></i>
                                                </span>
                                                <div class="timeline-event ps-1">
                                                    <div class="timeline-header">
                                                        <small
                                                            class="text-primary text-uppercase fw-medium">Receiver</small>
                                                    </div>
                                                    <h6 class="mb-2">Barry Schowalter</h6>
                                                    <p class="mb-0">939 Orange, California(CA), 92118</p>
                                                </div>
                                            </li>
                                        </ul>
                                        <div class="border-1 border-light border-top border-dashed mb-2 "></div>
                                        <ul class="timeline mb-0">
                                            <li class="timeline-item ps-4 border-left-dashed">
                                                <span
                                                    class="timeline-indicator-advanced text-success border-0 shadow-none">
                                                    <i class='mdi mdi-check-circle-outline'></i>
                                                </span>
                                                <div class="timeline-event ps-1">
                                                    <div class="timeline-header">
                                                        <small class="text-success text-uppercase fw-medium">sender</small>
                                                    </div>
                                                    <h6 class="mb-2">Myrtle Ullrich</h6>
                                                    <p class="mb-0">162 Windsor, California(CA), 95492 </p>
                                                </div>
                                            </li>
                                            <li class="timeline-item ps-4 border-transparent">
                                                <span
                                                    class="timeline-indicator-advanced text-primary border-0 shadow-none">
                                                    <i class='mdi mdi-map-marker-outline'></i>
                                                </span>
                                                <div class="timeline-event ps-1">
                                                    <div class="timeline-header">
                                                        <small
                                                            class="text-primary text-uppercase fw-medium">Receiver</small>
                                                    </div>
                                                    <h6 class="mb-2">Helen Jacobs</h6>
                                                    <p class="mb-0">487 Sunset, California(CA), 94043</p>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--/ Orders by Countries -->
                <!-- On route vehicles Table -->
                <div class="col-12 order-5">
                    <div class="card">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <div class="card-title mb-0">
                                <h5 class="m-0 me-2">On route vehicles</h5>
                            </div>
                            <div class="dropdown">
                                <button class="btn p-0" type="button" id="routeVehicles" data-bs-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    <i class="mdi mdi-dots-vertical mdi-24px"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="routeVehicles">
                                    <a class="dropdown-item" href="javascript:void(0);">Select All</a>
                                    <a class="dropdown-item" href="javascript:void(0);">Refresh</a>
                                    <a class="dropdown-item" href="javascript:void(0);">Share</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-datatable table-responsive">
                            <table class="dt-route-vehicles table">
                                <thead class="table-light">
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th>location</th>
                                        <th>starting route</th>
                                        <th>ending route</th>
                                        <th>warnings</th>
                                        <th class="w-20">progress</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!--/ On route vehicles Table -->


        </div>
        <!-- / Content -->




        <!-- Footer -->
        <footer class="content-footer footer bg-footer-theme">
            <div class="container-xxl">
                <div
                    class="footer-container d-flex align-items-center justify-content-between py-3 flex-md-row flex-column">
                    <div class="mb-2 mb-md-0">
                        ©
                        <script>
                            document.write(new Date().getFullYear())
                        </script>, made with <span class="text-danger"><i
                                class="tf-icons mdi mdi-heart"></i></span> by <a href="https://pixinvent.com"
                            target="_blank" class="footer-link fw-medium">Pixinvent</a>
                    </div>

                </div>
            </div>
        </footer>
        <!-- / Footer -->


        <div class="content-backdrop fade"></div>
    </div>
    <!-- / Content -->
@endsection()


@section('styles')
    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ asset('vendor/libs/flatpickr/flatpickr.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/libs/apex-charts/apex-charts.css') }}">

    <!-- Page CSS -->
    <link rel="stylesheet" href="{{ asset('vendor/css/pages/app-logistics-dashboard.css') }}">
@endsection()

@section('scripts')
    <!-- Vendors JS -->
    <script src="{{ asset('vendor/libs/flatpickr/flatpickr.js') }}"></script>
    <script src="{{ asset('vendor/libs/apex-charts/apexcharts.js') }}"></script>

    <!-- Page JS -->
    <script src="{{ asset('js/app-logistics-dashboard.js') }}"></script>
@endsection
