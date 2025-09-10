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
        s = $(".datatables-entries"),
        status = {
            unused: {
                title: "NO USADO",
                class: "badge rounded-pill bg-label-warning",
            },
            used: {
                title: "USADO",
                class: "badge rounded-pill bg-label-success",
            },
        };
    $("#flatpickr-range").flatpickr({
        mode: "range",
        dateFormat: "Y-m-d",
        defaultDate: [startDate, endDate],
        locale: {
            rangeSeparator: " Hasta ",
        },
        onChange: function (selectedDates, dateStr, instance) {
            if (selectedDates && selectedDates.length === 2) {
                $.blockUI({
                    message:
                        '<div class="sk-wave mx-auto"><div class="sk-rect sk-wave-rect"></div> <div class="sk-rect sk-wave-rect"></div> <div class="sk-rect sk-wave-rect"></div> <div class="sk-rect sk-wave-rect"></div> <div class="sk-rect sk-wave-rect"></div></div>',
                    css: { backgroundColor: "transparent", border: "0" },
                    overlayCSS: { opacity: 0.5 },
                });

                var Checked = $(".switch-input").prop("checked");
                var isChecked = Checked ? "1" : "0";

                var startDate = selectedDates[0].toISOString();
                var endDate = selectedDates[1].toISOString();
                $.ajax({
                    url: "Lista_Cortesias",
                    type: "GET",
                    data: {
                        startDate: startDate,
                        endDate: endDate,
                        isChecked: isChecked,
                    },
                })
                    .done((response) => {
                        e.clear().rows.add(response.data).draw();

                        // Procesar los datos recibidos para los totales
                        let totalCombos = response.data.length;
                        let usedPayments = 0;
                        let unusedPayments = 0;

                        response.data.forEach(function (row) {
                            if (
                                row.status &&
                                row.status.toLowerCase() === "used"
                            ) {
                                usedPayments++;
                            } else {
                                unusedPayments++;
                            }
                        });

                        // Actualizar los contadores
                        $("#total").text(totalCombos);
                        $("#shift1").text(usedPayments);
                        $("#shift2").text(unusedPayments);
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

    $("#flatpickr-date").flatpickr({
        monthSelectorType: "static",
        defaultDate: new Date(),
    });

    s.length &&
        (e = s.DataTable({
            ajax: {
                url: "Lista_Cortesias",
                data: function (d) {
                    var selectedDates = $("#flatpickr-range").val(); // Obtén las fechas seleccionadas

                    if (selectedDates) {
                        var dates = selectedDates.split(" Hasta "); // Separa las fechas
                        d.startDate = dates[0]; // Asigna la fecha de inicio
                        d.endDate = dates[1] ?? dates[0]; // Asigna la fecha de fin (puede ser la misma si no se seleccionó un rango)
                        d.isChecked = "0";
                    }
                },
            },
            columns: [
                { data: "id" },
                { data: "code" },
                { data: "names" },
                { data: "combos" },
                { data: "members" },
                { data: "validated_members" },
                { data: "validated_combos" },
                { data: "amount" },
                { data: "date_purchase" },
                { data: "date_issue" },
                { data: "status" },
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
                        return `<a href="Ver/${e}" target="_blank"><span>#${e}</span></a>`;
                    },
                },

                {
                    targets: 2,
                    render: function (e, t, a, n) {
                        return `${e} - ${a.document}`;
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
                    render: function (e, t, a, n) {
                        return e;
                    },
                },
                {
                    targets: -2,
                    render: function (e, t, a, n) {
                        const info = status[e] || {
                            title: "DESCONOCIDO",
                            class: "badge rounded-pill bg-label-secondary",
                        };
                        return `<span class="${info.class}">${info.title}</span>`;
                    },
                },
                {
                    targets: -1,
                    title: "Acciones",
                    render: function (a, e, t, s) {
                        // Si no está autorizado → mostrar botón "Autorizar"
                        if (!t.user_auth) {
                            return `
                            <div class="d-flex align-items-center">
                                <button
                                    class="btn btn-sm btn-success btnAuthorize"
                                    title="Autorizar">
                                    <i class="mdi mdi-check-circle-outline"></i> Autorizar
                                </button>
                            </div>`;
                        }

                        // Si ya está autorizado → mostrar botón de descarga de QR
                        return `
                        <div class="d-flex align-items-center">
                            <a href="qr/download/${t.code}"
                            class="btn btn-sm btn-primary"
                            data-bs-toggle="tooltip"
                            data-bs-placement="top"
                            title="Autorizado por: ${t.authorized_by_name} | Fecha: ${t.date_auth}">
                                <i class="mdi mdi-qrcode-plus"></i>
                            </a>
                        </div>`;
                    },
                },
            ],
            order: [[6, "desc"]],
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
                    text: '<i class="mdi mdi-export-variant me-1"></i> <span class="d-none d-sm-inline-block">Exportar</span>',
                    buttons: [
                        {
                            extend: "print",
                            text: '<i class="mdi mdi-printer-outline me-1" ></i>Print',
                            className: "dropdown-item",
                            exportOptions: {
                                columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
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
                                columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
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
                                columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
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
                                columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
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
                                columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
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
                    text: '<i class="mdi mdi-plus me-sm-1"></i> <span class="d-none d-sm-inline-block">Nuevo Pase cortesia</span>',
                    className:
                        "add-new btn btn-primary ms-n1 waves-effect waves-light",
                    action: function () {
                        window.location.href = "Nueva-Cortesia";
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
            initComplete: function () {
                var array = s.DataTable().rows().data();

                let totalCombos = array.length;
                let usedPayments = 0;
                let unusedPayments = 0;

                array.each(function (row) {
                    // Ajusta el nombre del campo según el backend
                    if (row.status == "used") {
                        usedPayments++;
                    } else {
                        unusedPayments++;
                    }
                });

                // Actualizar los contadores
                $("#total").text(totalCombos); // Total de pagos
                $("#shift1").text(usedPayments); // Pagos usados
                $("#shift2").text(unusedPayments); // Pagos no usados
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

    e.on("draw.dt", function () {
        $('[data-bs-toggle="tooltip"]').tooltip();
    });

    e.on("click", ".btnAuthorize", function () {
        let row = $(this).closest("tr");
        let rowData = $(this).closest("table").DataTable().row(row).data();

        Swal.fire({
            title: "¿Estás seguro?",
            text: `Autorizarás el uso del PAGO LINK: ${rowData.code}`,
            icon: "warning",
            input: "text", // 👈 Aquí el input
            inputPlaceholder: "Ingrese el código de validación",
            showCancelButton: true,
            confirmButtonText: "Sí, aceptar",
            cancelButtonText: "Cancelar",
            customClass: {
                confirmButton: "btn btn-primary me-3 waves-effect waves-light",
                cancelButton: "btn btn-outline-secondary waves-effect",
            },
            buttonsStyling: false,
            preConfirm: (codigo) => {
                if (!codigo) {
                    Swal.showValidationMessage("⚠️ Debes ingresar un código");
                }
                return codigo;
            },
        }).then(function (result) {
            if (result.isConfirmed) {
                blockUI();
                $.ajax({
                    url: "Autorize",
                    type: "post",
                    data: {
                        id: rowData.id,
                        code: result.value, // 👈 Capturamos el código ingresado
                        _token: csrfToken,
                    },
                })
                    .done((response) => {
                        e.ajax.reload();

                        Toast.fire({
                            icon: "success",
                            title: response.message,
                        });
                    })
                    .fail((response) => {
                        Toast.fire({
                            icon: "error",
                            title: response.responseText,
                        });
                        console.log(response.responseText);
                    })
                    .always(() => {
                        $.unblockUI();
                    });
            }
        });
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
