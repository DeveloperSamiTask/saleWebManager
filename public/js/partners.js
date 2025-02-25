"use strict";
$(function () {
    let t, a, n;
    n = (
        isDarkStyle
            ? ((t = config.colors_dark.borderColor),
              (a = config.colors_dark.bodyBg),
              config.colors_dark)
            : ((t = config.colors.borderColor),
              (a = config.colors.bodyBg),
              config.colors)
    ).headingColor;
    var today = new Date(),
        csrfToken = $('meta[name="csrf-token"]').attr("content");

    var oneMonthAgo = new Date();
    oneMonthAgo.setMonth(oneMonthAgo.getMonth() - 1);

    var startDate = formatDate(oneMonthAgo);
    var endDate = formatDate(today);
    var e,
        s = $(".datatables-entries");
    $("#flatpickr-range").flatpickr({
        mode: "range",
        dateFormat: "Y-m-d",
        defaultDate: [startDate, endDate],
        locale: {
            firstDayOfWeek: 1,
            rangeSeparator: " Hasta ",
            weekdays: {
                shorthand: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"],
                longhand: [
                    "Domingo",
                    "Lunes",
                    "Martes",
                    "Miércoles",
                    "Jueves",
                    "Viernes",
                    "Sábado",
                ],
            },
            months: {
                shorthand: [
                    "Ene",
                    "Feb",
                    "Mar",
                    "Abr",
                    "May",
                    "Jun",
                    "Jul",
                    "Ago",
                    "Sep",
                    "Оct",
                    "Nov",
                    "Dic",
                ],
                longhand: [
                    "Enero",
                    "Febrero",
                    "Marzo",
                    "Abril",
                    "Mayo",
                    "Junio",
                    "Julio",
                    "Agosto",
                    "Septiembre",
                    "Octubre",
                    "Noviembre",
                    "Diciembre",
                ],
            },
        },
        onChange: function (selectedDates, dateStr, instance) {
            if (selectedDates && selectedDates.length === 2) {
                $.blockUI({
                    message:
                        '<div class="sk-wave mx-auto"><div class="sk-rect sk-wave-rect"></div> <div class="sk-rect sk-wave-rect"></div> <div class="sk-rect sk-wave-rect"></div> <div class="sk-rect sk-wave-rect"></div> <div class="sk-rect sk-wave-rect"></div></div>',
                    css: { backgroundColor: "transparent", border: "0" },
                    overlayCSS: { opacity: 0.5 },
                });

                var startDate = selectedDates[0].toLocaleDateString("fr-CA"); // YYYY-MM-DD
                var endDate = selectedDates[1].toLocaleDateString("fr-CA"); // YYYY-MM-D

                $.ajax({
                    url: "partners_table",
                    type: "GET",
                    data: {
                        startDate: startDate,
                        endDate: endDate,
                    },
                })
                    .done((response) => {
                        const arr = response.data;

                        e.clear().rows.add(arr).draw();
                    })
                    .fail(function (error) {
                        console.error("error:", error.responseText);
                    })
                    .always(function (response) {
                        $.unblockUI();
                    });
            }
        },
    });

    $(".flatpickr-date").flatpickr({
        dateFormat: "d-m-Y",
        allowInput: true,
        defaultDate: endDate,
        locale: {
            firstDayOfWeek: 1,
            rangeSeparator: " Hasta ",
            weekdays: {
                shorthand: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"],
                longhand: [
                    "Domingo",
                    "Lunes",
                    "Martes",
                    "Miércoles",
                    "Jueves",
                    "Viernes",
                    "Sábado",
                ],
            },
            months: {
                shorthand: [
                    "Ene",
                    "Feb",
                    "Mar",
                    "Abr",
                    "May",
                    "Jun",
                    "Jul",
                    "Ago",
                    "Sep",
                    "Oct",
                    "Nov",
                    "Dic",
                ],
                longhand: [
                    "Enero",
                    "Febrero",
                    "Marzo",
                    "Abril",
                    "Mayo",
                    "Junio",
                    "Julio",
                    "Agosto",
                    "Septiembre",
                    "Octubre",
                    "Noviembre",
                    "Diciembre",
                ],
            },
        },
    });

    $("#initdate").flatpickr({
        dateFormat: "d-m-Y",
        defaultDate: today,
        altInput: true, // Muestra un input más amigable
        locale: {
            firstDayOfWeek: 1,
            rangeSeparator: " Hasta ",
            weekdays: {
                shorthand: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"],
                longhand: [
                    "Domingo",
                    "Lunes",
                    "Martes",
                    "Miércoles",
                    "Jueves",
                    "Viernes",
                    "Sábado",
                ],
            },
            months: {
                shorthand: [
                    "Ene",
                    "Feb",
                    "Mar",
                    "Abr",
                    "May",
                    "Jun",
                    "Jul",
                    "Ago",
                    "Sep",
                    "Oct",
                    "Nov",
                    "Dic",
                ],
                longhand: [
                    "Enero",
                    "Febrero",
                    "Marzo",
                    "Abril",
                    "Mayo",
                    "Junio",
                    "Julio",
                    "Agosto",
                    "Septiembre",
                    "Octubre",
                    "Noviembre",
                    "Diciembre",
                ],
            },
        },
        onChange: function (selectedDates) {
            if (selectedDates.length > 0) {
                let startDate = new Date(selectedDates[0]);
                let endDate = new Date(startDate);

                // Sumar 1 año y restar 1 día
                endDate.setFullYear(endDate.getFullYear() + 1);
                endDate.setDate(endDate.getDate() - 1);

                // Obtener día, mes y año correctamente formateados
                let day = String(endDate.getDate()).padStart(2, "0"); // 01-31
                let month = String(endDate.getMonth() + 1).padStart(2, "0"); // 01-12
                let year = endDate.getFullYear(); // YYYY

                let formattedEndDate = `${day}-${month}-${year}`; // Formato d-m-Y

                // Establecer valor en el input de vencimiento
                $("#enddate").val(formattedEndDate);
            }
        },
    });

    // Calculate the expiration date (one year minus one day)
    let expirationDate = new Date();
    expirationDate.setFullYear(today.getFullYear() + 1); // Add one year
    expirationDate.setDate(today.getDate() - 1); // Subtract one day

    // Format the date as "YYYY-MM-DD"
    let day = String(expirationDate.getDate()).padStart(2, "0"); // Ensure two digits
    let month = String(expirationDate.getMonth() + 1).padStart(2, "0"); // Ensure two digits (months are 0-based)
    let year = expirationDate.getFullYear();
    let formattedDate = `${day}-${month}-${year}`;
    // Set the value of the input field using jQuery
    $("#enddate").val(formattedDate);

    // Initialize Flatpickr on the input field
    $("#enddate").flatpickr({
        dateFormat: "d-m-Y",
        defaultDate: formattedDate,
        locale: {
            firstDayOfWeek: 1,
            rangeSeparator: " Hasta ",
            weekdays: {
                shorthand: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"],
                longhand: [
                    "Domingo",
                    "Lunes",
                    "Martes",
                    "Miércoles",
                    "Jueves",
                    "Viernes",
                    "Sábado",
                ],
            },
            months: {
                shorthand: [
                    "Ene",
                    "Feb",
                    "Mar",
                    "Abr",
                    "May",
                    "Jun",
                    "Jul",
                    "Ago",
                    "Sep",
                    "Oct",
                    "Nov",
                    "Dic",
                ],
                longhand: [
                    "Enero",
                    "Febrero",
                    "Marzo",
                    "Abril",
                    "Mayo",
                    "Junio",
                    "Julio",
                    "Agosto",
                    "Septiembre",
                    "Octubre",
                    "Noviembre",
                    "Diciembre",
                ],
            },
        },
    });

    s.length &&
        (e = s.DataTable({
            ajax: {
                url: "partners_table",
                data: function (d) {
                    var selectedDates = $("#flatpickr-range").val(); // Obtén las fechas seleccionadas

                    if (selectedDates) {
                        var dates = selectedDates.split(" Hasta "); // Separa las fechas
                        d.startDate = dates[0]; // Asigna la fecha de inicio
                        d.endDate = dates[1] ?? dates[0]; // Asigna la fecha de fin (puede ser la misma si no se seleccionó un rango)
                    }
                },
            },
            columns: [
                { data: "id" },
                { data: "card" },
                { data: "client" },
                { data: "document" },
                { data: "date_start" },
                { data: "date_end" },
                { data: "" },
                { data: "" },
            ],
            columnDefs: [
                {
                    className: "control",
                    searchable: !1,
                    orderable: !1,
                    responsivePriority: 2,
                    targets: 0,
                    render: function (e, t, a, n) {
                        return "";
                    },
                },

                {
                    targets: 1,
                    render: function (e, t, a, n) {
                        return e;
                    },
                },

                {
                    targets: 2,
                    render: function (e, t, a, n) {
                        return e;
                    },
                },
                {
                    targets: 3,
                    render: function (e, t, a, n) {
                        return e;
                    },
                },
                {
                    targets: 4,
                    className: "text-center",
                    render: function (a) {
                        if (!a) return "";
                        let date = new Date(a);
                        let day = date.getDate().toString().padStart(2, "0");
                        let month = (date.getMonth() + 1)
                            .toString()
                            .padStart(2, "0"); // Se suma 1 porque los meses van de 0 a 11
                        let year = date.getFullYear();
                        return `${day}/${month}/${year}`;
                    },
                },
                {
                    targets: 5,
                    render: function (a, e, t, s) {
                        if (!a) return "";
                        let date = new Date(a);
                        let day = date.getDate().toString().padStart(2, "0");
                        let month = (date.getMonth() + 1)
                            .toString()
                            .padStart(2, "0"); // Se suma 1 porque los meses van de 0 a 11
                        let year = date.getFullYear();
                        return `${day}/${month}/${year}`;
                    },
                },
                {
                    targets: 6,
                    title: "Estado",
                    render: function (a, e, t, s) {
                        let now = new Date();
                        let endDate = new Date(t.date_end);

                        return now > endDate
                            ? '<span class="badge rounded-pill bg-label-danger">Inactivo</span>'
                            : '<span class="badge rounded-pill bg-label-success">Activo</span>';
                    },
                },
                {
                    targets: -1,
                    title: "Acciones",
                    render: function (a, e, t, s) {
                        return `<div class="d-flex align-items-center">
                            <a href="javascript:;" data-bs-toggle="tooltip" class="text-body renew" data-bs-placement="top" title="Renovar Socio">
                                <i class="mdi mdi-credit-card-sync-outline fs-3 mx-1"></i>
                            </a>
                            <a href="javascript:;" data-bs-toggle="tooltip" class="text-body edit-record" data-bs-placement="top" title="Editar Socio">
                                <i class="mdi mdi-account-edit-outline fs-3 mx-1"></i>
                            </a>
                            </div>`;
                    },
                },
            ],
            order: [[0, "desc"]],
            dom: '<"row mx-2"<"col-md-2"<"me-3"l>><"col-md-10"<"dt-action-buttons text-xl-end text-lg-start text-md-end text-start d-flex align-items-center justify-content-end flex-md-row flex-column mb-3 mb-md-0 gap-3"fB>>>t<"row mx-2"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
            language: {
                sLengthMenu: "Mostrar _MENU_",
                search: "",
                searchPlaceholder: "Buscar..",
            },
            buttons: [
                {
                    extend: "collection",
                    className:
                        "btn btn-label-secondary dropdown-toggle me-3 waves-effect waves-light",
                    text: '<i class="mdi mdi-export-variant me-1"></i> <span class="d-none d-sm-inline-block">Export</span>',
                    buttons: [
                        {
                            extend: "print",
                            text: '<i class="mdi mdi-printer-outline me-1" ></i>Print',
                            className: "dropdown-item",
                            exportOptions: {
                                columns: [1, 2, 3, 4, 5, 6, 7, 8],
                                format: {
                                    body: function (e, t, a) {
                                        var n;
                                        return e.length <= 0
                                            ? e
                                            : ((e = $.parseHTML(e)),
                                              (n = ""),
                                              $.each(e, function (e, t) {
                                                  void 0 !== t.classList &&
                                                  t.classList.contains(
                                                      "user-name"
                                                  )
                                                      ? (n +=
                                                            t.lastChild
                                                                .firstChild
                                                                .textContent)
                                                      : void 0 === t.innerText
                                                      ? (n += t.textContent)
                                                      : (n += t.innerText);
                                              }),
                                              n);
                                    },
                                },
                            },
                            customize: function (e) {
                                $(e.document.body)
                                    .css("color", n)
                                    .css("border-color", t)
                                    .css("background-color", a),
                                    $(e.document.body)
                                        .find("table")
                                        .addClass("compact")
                                        .css("color", "inherit")
                                        .css("border-color", "inherit")
                                        .css("background-color", "inherit");
                            },
                        },
                        {
                            extend: "csv",
                            text: '<i class="mdi mdi-file-document-outline me-1" ></i>Csv',
                            className: "dropdown-item",
                            exportOptions: {
                                columns: [1, 2, 3, 4, 5, 6, 7, 8],
                                format: {
                                    body: function (e, t, a) {
                                        var n;
                                        return e.length <= 0
                                            ? e
                                            : ((e = $.parseHTML(e)),
                                              (n = ""),
                                              $.each(e, function (e, t) {
                                                  void 0 !== t.classList &&
                                                  t.classList.contains(
                                                      "user-name"
                                                  )
                                                      ? (n +=
                                                            t.lastChild
                                                                .firstChild
                                                                .textContent)
                                                      : void 0 === t.innerText
                                                      ? (n += t.textContent)
                                                      : (n += t.innerText);
                                              }),
                                              n);
                                    },
                                },
                            },
                        },
                        {
                            extend: "excel",
                            text: '<i class="mdi mdi-file-excel-outline me-1"></i>Excel',
                            className: "dropdown-item",
                            exportOptions: {
                                columns: [1, 2, 3, 4, 5, 6, 7, 8],
                                format: {
                                    body: function (e, t, a) {
                                        var n;
                                        return e.length <= 0
                                            ? e
                                            : ((e = $.parseHTML(e)),
                                              (n = ""),
                                              $.each(e, function (e, t) {
                                                  void 0 !== t.classList &&
                                                  t.classList.contains(
                                                      "user-name"
                                                  )
                                                      ? (n +=
                                                            t.lastChild
                                                                .firstChild
                                                                .textContent)
                                                      : void 0 === t.innerText
                                                      ? (n += t.textContent)
                                                      : (n += t.innerText);
                                              }),
                                              n);
                                    },
                                },
                            },
                        },
                        {
                            extend: "pdf",
                            text: '<i class="mdi mdi-file-pdf-box me-1"></i>Pdf',
                            className: "dropdown-item",
                            exportOptions: {
                                columns: [1, 2, 3, 4, 5, 6, 7, 8],
                                format: {
                                    body: function (e, t, a) {
                                        var n;
                                        return e.length <= 0
                                            ? e
                                            : ((e = $.parseHTML(e)),
                                              (n = ""),
                                              $.each(e, function (e, t) {
                                                  void 0 !== t.classList &&
                                                  t.classList.contains(
                                                      "user-name"
                                                  )
                                                      ? (n +=
                                                            t.lastChild
                                                                .firstChild
                                                                .textContent)
                                                      : void 0 === t.innerText
                                                      ? (n += t.textContent)
                                                      : (n += t.innerText);
                                              }),
                                              n);
                                    },
                                },
                            },
                        },
                        {
                            extend: "copy",
                            text: '<i class="mdi mdi-content-copy me-1"></i>Copy',
                            className: "dropdown-item",
                            exportOptions: {
                                columns: [1, 2, 3, 4, 5, 6, 7, 8],
                                format: {
                                    body: function (e, t, a) {
                                        var n;
                                        return e.length <= 0
                                            ? e
                                            : ((e = $.parseHTML(e)),
                                              (n = ""),
                                              $.each(e, function (e, t) {
                                                  void 0 !== t.classList &&
                                                  t.classList.contains(
                                                      "user-name"
                                                  )
                                                      ? (n +=
                                                            t.lastChild
                                                                .firstChild
                                                                .textContent)
                                                      : void 0 === t.innerText
                                                      ? (n += t.textContent)
                                                      : (n += t.innerText);
                                              }),
                                              n);
                                    },
                                },
                            },
                        },
                    ],
                },
                {
                    text: '<i class="mdi mdi-plus me-sm-1"></i> <span class="d-none d-sm-inline-block">Agregar Socio</span>',
                    className:
                        "create-new btn btn-primary waves-effect waves-light",
                    attr: {
                        "data-bs-toggle": "modal",
                        "data-bs-target": "#addNewCoupon",
                    },
                    init: function (e, t, a) {
                        $(t).removeClass("btn-secondary");
                    },
                },
            ],
            responsive: {
                details: {
                    display: $.fn.dataTable.Responsive.display.modal({
                        header: function (e) {
                            return "Details of " + e.data().full_name;
                        },
                    }),
                    type: "column",
                    renderer: function (e, t, a) {
                        a = $.map(a, function (e, t) {
                            return "" !== e.title
                                ? '<tr data-dt-row="' +
                                      e.rowIndex +
                                      '" data-dt-column="' +
                                      e.columnIndex +
                                      '"><td>' +
                                      e.title +
                                      ":</td> <td>" +
                                      e.data +
                                      "</td></tr>"
                                : "";
                        }).join("");
                        return (
                            !!a &&
                            $('<table class="table"/><tbody />').append(a)
                        );
                    },
                },
            },
        })),
        setTimeout(() => {
            $(".dataTables_filter .form-control").removeClass(
                "form-control-sm"
            ),
                $(".dataTables_length .form-select").removeClass(
                    "form-select-sm"
                );
        }, 300);

    e.on("click", ".btn-acepted", function () {
        let row = $(this).closest("tr");
        let rowData = $(this).closest("table").DataTable().row(row).data();

        Swal.fire({
            title: "Estas seguro?",
            text: `Aceptaras el Cambio de Documento de la Entrada: ${rowData.detcart}`,
            icon: "warning",
            showCancelButton: !0,
            confirmButtonText: "Si, aceptar",
            cancelButtonText: "Cancelar",
            customClass: {
                confirmButton: "btn btn-primary me-3 waves-effect waves-light",
                cancelButton: "btn btn-outline-secondary waves-effect",
            },
            buttonsStyling: !1,
        }).then(function (t) {
            if (t.value) {
                $.blockUI({
                    message:
                        '<div class="sk-wave mx-auto"><div class="sk-rect sk-wave-rect"></div> <div class="sk-rect sk-wave-rect"></div> <div class="sk-rect sk-wave-rect"></div> <div class="sk-rect sk-wave-rect"></div> <div class="sk-rect sk-wave-rect"></div></div>',
                    css: { backgroundColor: "transparent", border: "0" },
                    overlayCSS: { opacity: 0.5 },
                });
                $.ajax({
                    url: "updatedDocument",
                    type: "post",
                    data: {
                        id: rowData.id,
                        detcart: rowData.detcart,
                        document: rowData.dniAfter,
                        _token: csrfToken,
                    },
                })
                    .done((response) => {
                        console.log(response);
                        e.ajax.reload();

                        Toast.fire({
                            icon: "success",
                            title: "Se ha cambiad el Documento",
                        });
                    })
                    .fail((response) => {
                        Toast.fire({
                            icon: "error",
                            title: "Error al cambiar documento, contactar con SISTEMAS",
                        });
                        console.log(response.responseText);
                    })
                    .always(() => {
                        $.unblockUI();
                    });
            }
        });
    });

    e.on("click", ".renew", function () {
        let row = $(this).closest("tr");
        let rowData = $(this).closest("table").DataTable().row(row).data();

        $("#renew-modal").modal("show");
    });

    $("#searchPartner").on("click", function () {
        $("#renew-modal").modal("show");
    });

    $("#selectSearch").on("change", function () {
        let text = $("#selectSearch option:selected").text();
        $("#searchInput").attr("placeholder", `Buscar por ${text}`);
    });

    $("#renewInitdate").flatpickr({
        dateFormat: "d-m-Y",
        defaultDate: today,
        locale: {
            firstDayOfWeek: 1,
            rangeSeparator: " Hasta ",
            weekdays: {
                shorthand: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"],
                longhand: [
                    "Domingo",
                    "Lunes",
                    "Martes",
                    "Miércoles",
                    "Jueves",
                    "Viernes",
                    "Sábado",
                ],
            },
            months: {
                shorthand: [
                    "Ene",
                    "Feb",
                    "Mar",
                    "Abr",
                    "May",
                    "Jun",
                    "Jul",
                    "Ago",
                    "Sep",
                    "Oct",
                    "Nov",
                    "Dic",
                ],
                longhand: [
                    "Enero",
                    "Febrero",
                    "Marzo",
                    "Abril",
                    "Mayo",
                    "Junio",
                    "Julio",
                    "Agosto",
                    "Septiembre",
                    "Octubre",
                    "Noviembre",
                    "Diciembre",
                ],
            },
        },
        onChange: function (selectedDates) {
            if (selectedDates.length > 0) {
                let renewalDate = selectedDates[0];

                // Calcular fecha de vencimiento (1 año - 1 día)
                let expirationDate = new Date(renewalDate);
                expirationDate.setFullYear(expirationDate.getFullYear() + 1); // +1 año
                expirationDate.setDate(expirationDate.getDate() - 1); // -1 día

                // Extraer día, mes y año
                let day = expirationDate.getDate().toString().padStart(2, "0");
                let month = (expirationDate.getMonth() + 1)
                    .toString()
                    .padStart(2, "0"); // Meses van de 0 a 11
                let year = expirationDate.getFullYear();

                let formattedEndDate = `${day}-${month}-${year}`; // Formato d-m-Y

                // Asignar la fecha de vencimiento
                $("#renewEnddate").val(formattedEndDate);
            }
        },
    });

    // Inicializar el campo de vencimiento (solo lectura)
    $("#renewEnddate").flatpickr({
        dateFormat: "d-m-Y",
        defaultDate: formattedDate,
        locale: {
            firstDayOfWeek: 1,
            rangeSeparator: " Hasta ",
            weekdays: {
                shorthand: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"],
                longhand: [
                    "Domingo",
                    "Lunes",
                    "Martes",
                    "Miércoles",
                    "Jueves",
                    "Viernes",
                    "Sábado",
                ],
            },
            months: {
                shorthand: [
                    "Ene",
                    "Feb",
                    "Mar",
                    "Abr",
                    "May",
                    "Jun",
                    "Jul",
                    "Ago",
                    "Sep",
                    "Oct",
                    "Nov",
                    "Dic",
                ],
                longhand: [
                    "Enero",
                    "Febrero",
                    "Marzo",
                    "Abril",
                    "Mayo",
                    "Junio",
                    "Julio",
                    "Agosto",
                    "Septiembre",
                    "Octubre",
                    "Noviembre",
                    "Diciembre",
                ],
            },
        },
    });

    $("#btnSearch").on("click", function () {
        blockUI();
        $.ajax({
            url: "searchPartner",
            type: "post",
            data: {
                search: $("#searchInput").val(),
                select: $("#selectSearch").val(),
                _token: csrfToken,
            },
        })
            .done((data) => {
                $("#hiddenCode").val(data.cClieCode);
                $("#codeRenew").val(data.cClieCode);
                $("#namesRenew").val(`${data.sClieApel} ${data.sClieName}`);
                $("#docRenew").val(data.charClienteDni);
                $("#birthdateRenew").val(formatDateToDMY(data.dNacmDate));
                $("#renewInitdate").val(
                    formatDateToDMY(data.partners[0].dEmisDate)
                );
                $("#renewEnddate").val(
                    formatDateToDMY(data.partners[0].dCaduDate)
                );

                $(".btnRenew").prop("disabled", false);
            })
            .fail((response) => {
                Toast.fire({
                    icon: response.icon,
                    title: response.message,
                });
            })
            .always(() => {
                $.unblockUI();
            });
    });

    $("#renewForm").on("submit", function (h) {
        h.preventDefault();
        blockUI();

        if ($("#renewAffiliation").val() == "") {
            Toast.fire({
                icon: "error",
                title: "Debe ingresar la ficha de afiliación",
            });
            $.unblockUI();

            return;
        }

        let formData = new FormData(this);
        formData.append("_token", csrfToken);

        fetch("renewPartner", {
            method: "POST",
            body: formData,
        })
            .then((response) => {
                if (!response.ok) {
                    throw new Error("Error en la solicitud");
                }
                return response.json();
            })
            .then((data) => {
                e.ajax.reload();
                Toast.fire({
                    icon: data.icon,
                    title: data.message,
                });

                $("#renew-modal").modal("hide");
            })
            .catch((error) => {
                Toast.fire({
                    icon: error.icon,
                    title: error.message,
                });
            })
            .finally(() => {
                $.unblockUI();
            });
    });

    const f = document.getElementById("partnerForm");

    const fv = FormValidation.formValidation(f, {
        fields: {
            pattername: {
                validators: {
                    notEmpty: {
                        message: "Ingresa el apellido paterno del socio",
                    },
                },
            },
            mattername: {
                validators: {
                    notEmpty: {
                        message: "Ingresa el apellido materno del socio",
                    },
                },
            },
            names: {
                validators: {
                    notEmpty: { message: "Ingresa nombres del socio" },
                },
            },
            doc: {
                validators: {
                    notEmpty: {
                        message: "Ingresa el Nº documento del socio",
                    },
                },
            },
            birthdate: {
                validators: {
                    notEmpty: { message: "Ingresa la fecha de nacimiento" },
                },
            },
            affiliation: {
                validators: {
                    notEmpty: { message: "Ingresa la ficha de afilicación" },
                },
            },
            initdate: {
                validators: {
                    notEmpty: { message: "Ingresa la fecha de inicio" },
                },
            },
            enddate: {
                validators: {
                    notEmpty: { message: "Ingresa la fecha de vencimiento" },
                },
            },
            address: {
                validators: {
                    notEmpty: { message: "Ingresa la dirección" },
                },
            },
            mail: {
                validators: {
                    notEmpty: {
                        message: "Ingresa el e-mail",
                    },
                    emailAddress: {
                        message: "Ingresa un e-mail válido",
                    },
                },
            },
            phone: {
                validators: {
                    notEmpty: {
                        message: "Ingresa el número de celular",
                    },
                },
            },
        },
        plugins: {
            trigger: new FormValidation.plugins.Trigger(),
            bootstrap5: new FormValidation.plugins.Bootstrap5({
                eleValidClass: "is-valid",
                rowSelector: function (t, e) {
                    switch (e) {
                        case "formValidationName":
                        case "formValidationEmail":
                        case "formValidationPass":
                        case "formValidationConfirmPass":
                        case "formValidationFile":
                        case "formValidationDob":
                        case "formValidationSelect2":
                        case "formValidationLang":
                        case "formValidationTech":
                        case "formValidationHobbies":
                        case "formValidationBio":
                        case "formValidationGender":
                            return ".col-md-6";
                        case "formValidationPlan":
                            return ".col-xl-3";
                        case "formValidationSwitch":
                        case "formValidationCheckbox":
                            return ".col-12";
                        default:
                            return ".row";
                    }
                },
            }),
            submitButton: new FormValidation.plugins.SubmitButton(),
            autoFocus: new FormValidation.plugins.AutoFocus(),
        },
    });

    fv.on("core.form.valid", function () {
        blockUI();

        let formData = new FormData(f);
        formData.append("_token", csrfToken);

        fetch("insertPartner", {
            method: "POST",
            body: formData,
        })
            .then((response) => {
                if (!response.ok) {
                    throw new Error("Error en la solicitud");
                }
                return response.json();
            })
            .then((data) => {
                e.ajax.reload();
                Toast.fire({
                    icon: data.icon,
                    title: data.message,
                });

                $("#addNewCoupon").modal("hide");
            })
            .catch((error) => {
                console.error("Error:", error);
                Toast.fire({
                    icon: error.icon,
                    title: error.message,
                });
            })
            .finally(() => {
                $.unblockUI();
            });
    });

    $("#editPartner").on("click", function (h) {
        $("#edit-modal").modal("show");
    });

    $("#editSelect").on("change", function () {
        let text = $("#editSelect option:selected").text();
        $("#inputSelect").attr("placeholder", `Buscar por ${text}`);
    });

    $("#editBtn").on("click", function () {
        blockUI();
        $.ajax({
            url: "searchPartner",
            type: "post",
            data: {
                search: $("#inputSelect").val(),
                select: $("#editSelect").val(),
                _token: csrfToken,
            },
        })
            .done((data) => {
                console.log(data);

                $("#editCodeHidden").val(data.cClieCode);
                $("#editcode").val(data.cClieCode);
                $("#editpattername").val(`${data.sClieApepat}`);
                $("#editmattername").val(`${data.sClieApemat}`);
                $("#editnames").val(`${data.sClieName}`);
                $("#editdoc").val(data.charClienteDni);
                $("#editbirthdate").val(formatDateToDMY(data.dNacmDate));
                $("#editaffiliation").val(data.partners[0].affiliation);
                $("#editinitdate").val(
                    formatDateToDMY(data.partners[0].dEmisDate)
                );
                $("#editenddate").val(
                    formatDateToDMY(data.partners[0].dCaduDate)
                );
                $("#editaddress").val(data.sClieAddr);
                $("#editphone").val(data.sClieTelf);
                $("#editmail").val(data.sClieMail);

                if(data.proxy){
                    $("#EditaccordionOne").collapse("show");
                    $("#editproxyPatter").val(data.proxy.proxy_pattername);
                    $("#editproxyMatter").val(data.proxy.proxy_mattername);
                    $("#editproxyNames").val(data.proxy.proxy_names);
                    $("#editproxyDoc").val(data.proxy.proxy_doc);
                }

            })
            .fail((response) => {
                Toast.fire({
                    icon: response.icon,
                    title: response.message,
                });
            })
            .always(() => {
                $.unblockUI();
            });
    });

    $("#addNewCoupon").on("hidden.bs.modal", function () {
        $("#partnerForm")[0].reset();
        fv.resetForm(true);
    });

    $("#renew-modal").on("hidden.bs.modal", function () {
        $("#renewForm")[0].reset();
        fv.resetForm(true);
        $("#selectSearch").val("charClienteDni").trigger("change");
        $(".btnRenew").prop("disabled", true);
    });

    $("#edit-modal").on("hidden.bs.modal", function () {
        $("#editForm")[0].reset();
        fv.resetForm(true);
    });

    function formatDate(date) {
        var year = date.getFullYear();
        var month = (date.getMonth() + 1).toString().padStart(2, "0");
        var day = date.getDate().toString().padStart(2, "0");
        var hours = date.getHours().toString().padStart(2, "0");
        var minutes = date.getMinutes().toString().padStart(2, "0");
        var seconds = date.getSeconds().toString().padStart(2, "0");
        return `${year}-${month}-${day}T${hours}:${minutes}:${seconds}`;
    }

    function formatDateToDMY(dateString) {
        if (!dateString) return ""; // Si la fecha es null o vacía, retorna vacío

        let dateObj = new Date(dateString);
        if (isNaN(dateObj)) return ""; // Verifica si la fecha es válida

        let day = ("0" + dateObj.getDate()).slice(-2);
        let month = ("0" + (dateObj.getMonth() + 1)).slice(-2);
        let year = dateObj.getFullYear();

        return `${day}-${month}-${year}`;
    }

    function blockUI() {
        $.blockUI({
            message:
                '<div class="sk-wave mx-auto"><div class="sk-rect sk-wave-rect"></div> <div class="sk-rect sk-wave-rect"></div> <div class="sk-rect sk-wave-rect"></div> <div class="sk-rect sk-wave-rect"></div> <div class="sk-rect sk-wave-rect"></div></div>',
            css: { backgroundColor: "transparent", border: "0" },
            overlayCSS: { opacity: 0.5 },
        });
    }

    const Toast = Swal.mixin({
        toast: true,
        position: "top",
        showConfirmButton: false,
        timer: 4000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.onmouseenter = Swal.stopTimer;
            toast.onmouseleave = Swal.resumeTimer;
        },
    });
}),
    (function () {})();
