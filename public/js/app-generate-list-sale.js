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
    var today = new Date();

    $("#stadistics").block({
        message:
            '<div class="sk-wave mx-auto"><div class="sk-rect sk-wave-rect"></div> <div class="sk-rect sk-wave-rect"></div> <div class="sk-rect sk-wave-rect"></div> <div class="sk-rect sk-wave-rect"></div> <div class="sk-rect sk-wave-rect"></div></div>',
        css: { backgroundColor: "transparent", color: "#fff", border: "0" },
        overlayCSS: { opacity: 0.5 },
    });

    // Obtener la fecha exactamente un mes atrás
    var oneMonthAgo = new Date();
    oneMonthAgo.setMonth(oneMonthAgo.getMonth() - 1);

    // Formatear las fechas en formato ISO
    var startDate = formatDate(oneMonthAgo);
    var endDate = formatDate(today);
    var e,
        s = $(".datatables-entries"),
        i = $(".select2"),
        o = {
            1: { title: "Pending", class: "bg-label-warning" },
            2: { title: "Active", class: "bg-label-success" },
            3: { title: "Inactive", class: "bg-label-secondary" },
        },
        status = {
            1: {
                title: "USADO",
                class: "badge rounded-pill bg-label-success",
            },
            0: {
                title: "SIN USO",
                class: "badge rounded-pill bg-label-danger",
            },
        };
    const sure = {
            0: { title: "DESACTIVADO" },
            1: { title: "ACTIVADO" },
        },
        shift = {
            1: { title: "TURNO COMPLETO" },
            2: { title: "AFTER SCHOOL" },
        },
        device = {
            Seleccione: { title: "SIN DISPOSITIVO" },
            Tarjeta: { title: "TARJETA" },
            Portatarjeta: { title: "TARJETA + LANGER" },
            Pulserasilicona: { title: "PULSERA SILICONA" },
            Pulserafashion: { title: "PULSERA SILICONA AJUSTABLE" },
        },
        entrie = {
            1: { title: "Entrada Navidad" },
            2: { title: "Entrada Dinamica" },
            6: { title: "Entrada VIP Terror" },
            11: { title: "Entrada General Terror" },
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

                console.log(isChecked);

                var startDate = selectedDates[0].toISOString();
                var endDate = selectedDates[1].toISOString();
                console.log(startDate, endDate);
                $.ajax({
                    url: "Tabla_Entradas",
                    type: "GET",
                    data: {
                        start_date: startDate,
                        end_date: endDate,
                        isChecked: isChecked,
                    },
                })
                    .done((response) => {
                        const arr = response.data;

                        var contadorShift1 = 0;
                        var contadorShift2 = 0;

                        arr.forEach(function (registro) {
                            if (registro.shift === "1") {
                                contadorShift1++;
                            } else if (registro.shift === "2") {
                                contadorShift2++;
                            }
                        });
                        $("#total").text(arr.length);
                        $("#shift1").text(contadorShift1);
                        $("#shift2").text(contadorShift2);
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
    i.length &&
        i.length &&
        i.each(function () {
            var i = $(this);
            select2Focus(i),
                i.wrap('<div class="position-relative"></div>').select2({
                    dropdownParent: i.parent(),
                    placeholder: i.data("placeholder"),
                });
        }),
        s.length &&
            (e = s.DataTable({
                ajax: "Tabla_Entradas",
                columns: [
                    { data: "" },
                    { data: "code" },
                    { data: "id" },
                    { data: "nameP" },
                    { data: "name" },
                    { data: "entrie" },
                    { data: "shift" },
                    { data: "device" },
                    { data: "price" },
                    { data: "purchase" },
                    { data: "income" },
                    { data: "status" },
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
                            return `<a href="javascript:void(0)"><span>${e}</span></a>`;
                        },
                    },

                    {
                        targets: 2,
                        render: function (e, t, a, n) {
                            return `<a href="javascript:void(0)"><span>${e}</span></a>`;
                        },
                    },
                    {
                        targets: 3,
                        visible: !1,
                        render: function (e, t, a, n) {
                            return e;
                        },
                    },
                    {
                        targets: 5,
                        render: function (e, t, a, n) {
                            return entrie[e].title;
                        },
                    },
                    {
                        targets: 6,
                        render: function (a, e, t, s) {
                            return shift[a]?.title ?? "Turno Completo";
                        },
                    },
                    {
                        targets: 7,
                        render: function (a, e, t, s) {
                            return device[a]?.title ?? "No seleccionado";
                        },
                    },
                    {
                        targets: -1,
                        render: function (a) {
                            return (
                                '<span class="' +
                                status[a].class +
                                '" text-capitalized="">' +
                                status[a].title +
                                "</span>"
                            );
                        },
                    },
                ],
                order: [[9, "desc"]],
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
                                    columns: [
                                        1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11,
                                    ],
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
                                                          : void 0 ===
                                                            t.innerText
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
                                    columns: [
                                        1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11,
                                    ],
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
                                                          : void 0 ===
                                                            t.innerText
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
                                    columns: [
                                        1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11,
                                    ],
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
                                                          : void 0 ===
                                                            t.innerText
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
                                    columns: [
                                        1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11,
                                    ],
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
                                                          : void 0 ===
                                                            t.innerText
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
                                    columns: [
                                        1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11,
                                    ],
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
                                                          : void 0 ===
                                                            t.innerText
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

                    var contadorShift1 = 0;
                    var contadorShift2 = 0;

                    array.each(function (registro) {
                        if (registro.shift === "1") {
                            contadorShift1++;
                        } else if (registro.shift === "2") {
                            contadorShift2++;
                        }
                    });

                    $("#total").text(array.length);
                    $("#shift1").text(contadorShift1);
                    $("#shift2").text(contadorShift2);
                    this.api()
                        .columns(6)
                        .every(function () {
                            var t = this,
                                a = $(
                                    '<select id="UserRole" class="select2 form-select text-capitalize"><option value=""> Seleccionar Turno </option></select>'
                                )
                                    .appendTo(".user_role")
                                    .on("change", function () {
                                        var e = $.fn.dataTable.util.escapeRegex(
                                            $(this).val()
                                        );
                                        t.search(
                                            e ? "^" + e + "$" : "",
                                            !0,
                                            !1
                                        ).draw();
                                    });
                            t.data()
                                .unique()
                                .sort()
                                .each(function (e, t) {
                                    a.append(
                                        '<option value="' +
                                            shift[e].title +
                                            '">' +
                                            shift[e].title +
                                            "</option>"
                                    );
                                });
                        }),
                        this.api()
                            .columns(7)
                            .every(function () {
                                var t = this,
                                    a = $(
                                        '<select id="UserPlan" class="select2 form-select text-capitalize"><option value=""> Seleccionar Dispositivo</option></select>'
                                    )
                                        .appendTo(".user_plan")
                                        .on("change", function () {
                                            var e =
                                                $.fn.dataTable.util.escapeRegex(
                                                    $(this).val()
                                                );
                                            t.search(
                                                e ? "^" + e + "$" : "",
                                                !0,
                                                !1
                                            ).draw();
                                        });
                                t.data()
                                    .unique()
                                    .sort()
                                    .each(function (e, t) {
                                        a.append(
                                            '<option value="' +
                                                device[e].title +
                                                '">' +
                                                device[e].title +
                                                "</option>"
                                        );
                                    });
                            }),
                        this.api()
                            .columns(11)
                            .every(function () {
                                var t = this,
                                    a = $(
                                        '<select id="FilterTransaction" class="select2 form-select text-capitalize"><option value=""> Seleccionar Estado </option></select>'
                                    )
                                        .appendTo(".user_status")
                                        .on("change", function () {
                                            var e =
                                                $.fn.dataTable.util.escapeRegex(
                                                    $(this).val()
                                                );
                                            t.search(
                                                e ? "^" + e + "$" : "",
                                                !0,
                                                !1
                                            ).draw();
                                        });
                                t.data()
                                    .unique()
                                    .sort()
                                    .each(function (e, t) {
                                        a.append(
                                            '<option value="' +
                                                status[e].title +
                                                '" class="text-capitalize">' +
                                                status[e].title +
                                                "</option>"
                                        );
                                    });
                            });
                    $("#stadistics").unblock();
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
    function formatDate(date) {
        var year = date.getFullYear();
        var month = (date.getMonth() + 1).toString().padStart(2, "0");
        var day = date.getDate().toString().padStart(2, "0");
        var hours = date.getHours().toString().padStart(2, "0");
        var minutes = date.getMinutes().toString().padStart(2, "0");
        var seconds = date.getSeconds().toString().padStart(2, "0");
        return `${year}-${month}-${day}T${hours}:${minutes}:${seconds}`;
    }
}),
    (function () {})();
