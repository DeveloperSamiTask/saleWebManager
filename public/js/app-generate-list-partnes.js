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
        i = $(".select2");

    $("#flatpickr-range").flatpickr({
        mode: "range",
        dateFormat: "Y-m-d",
        defaultDate: [startDate, endDate],
        locale: {
            rangeSeparator: " Hasta ",
        },
        onChange: function (selectedDates, dateStr, instance) {
            if (selectedDates && selectedDates.length === 2) {
                blockUI();

                var startDate = selectedDates[0].toISOString().split("T")[0]; // Formato YYYY-MM-DD
                var endDate = selectedDates[1].toISOString().split("T")[0]; // Formato YYYY-MM-DD

                console.log("startDate", startDate);
                console.log("endDate", endDate);

                $.ajax({
                    url: "tableValidate",
                    type: "GET",
                    data: {
                        startDate: startDate,
                        endDate: endDate,
                    },
                })
                    .done((response) => {
                        const count = response.count;

                        $("#birthday").text(count.birthday);
                        $("#nmagic").text(count.magic);

                        e.clear().rows.add(response.data).draw();
                    })
                    .fail(function (error) {
                        console.error("Error:", error.responseText);
                    })
                    .always(function () {
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
                ajax: {
                    url: "tableValidate",
                    data: function (d) {
                        blockUI();

                        var selectedDates = $("#flatpickr-range").val(); // Obtén las fechas seleccionadas
                        if (selectedDates) {
                            var dates = selectedDates.split(" Hasta "); // Separa las fechas
                            d.startDate = dates[0]; // Asigna la fecha de inicio
                            d.endDate = dates[1] ?? dates[0]; // Asigna la fecha de fin (puede ser la misma si no se seleccionó un rango)
                        }

                        console.log("data", d);
                    },
                    dataSrc: function (json) {
                        $("#birthday").text(json.count.birthday);
                        $("#nmagic").text(json.count.magic);

                        return json.data;
                    },
                },
                columns: [
                    { data: "id" },
                    { data: "card" },
                    { data: "document" },
                    { data: "client" },
                    { data: "type" },
                    { data: "birthday" },
                    { data: "register" },
                    { data: "date" },
                    { data: "user" },
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
                        targets: [3,8],
                        render: function (e, t, a, n) {
                            return `<span class="text-heading fw-medium">${e}</span>`;
                        },
                    },
                    {
                        targets: 4,
                        render: function (e, t, a, n) {
                            return e;
                        },
                    },
                    {
                        targets: [5,6],
                        className: "text-center",
                        render: function (e, t, a, n) {
                            return e ? e.split(" ")[0] : "";
                        },
                    },
                ],
                order: [[7, "desc"]],
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
                                        1, 2, 3, 4, 5, 6, 7, 8
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
                                        1, 2, 3, 4, 5, 6, 7, 8
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
                                        1, 2, 3, 4, 5, 6, 7, 8
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
                                        1, 2, 3, 4, 5, 6, 7, 8
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
                                        1, 2, 3, 4, 5, 6, 7, 8
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
                    this.api()
                        .columns(4)
                        .every(function () {
                            var t = this,
                                a = $(
                                    '<select id="UserRole" class="select2 form-select text-capitalize"><option value=""> Filtrar CUPON </option></select>'
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
                                            e +
                                            '">' +
                                            e +
                                            "</option>"
                                    );
                                });
                        }),
                        this.api()
                            .columns(8)
                            .every(function () {
                                var t = this,
                                    a = $(
                                        '<select id="UserPlan" class="select2 form-select text-capitalize"><option value=""> Filtrar CAJER@</option></select>'
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
                                                e +
                                                '">' +
                                                e +
                                                "</option>"
                                        );
                                    });
                            });
                    $("#stadistics").unblock();
                },
            })),
        // Evento que se ejecuta cuando el AJAX de DataTables termina
        e.on("xhr.dt", function (e, settings, json, xhr) {
            $.unblockUI();
        });
    setTimeout(() => {
        $(".dataTables_filter .form-control").removeClass("form-control-sm"),
            $(".dataTables_length .form-select").removeClass("form-select-sm");
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

    function blockUI() {
        $.blockUI({
            message: `<div class="banter-loader">
                  <div class="banter-loader__box"></div>
                  <div class="banter-loader__box"></div>
                  <div class="banter-loader__box"></div>
                  <div class="banter-loader__box"></div>
                  <div class="banter-loader__box"></div>
                  <div class="banter-loader__box"></div>
                  <div class="banter-loader__box"></div>
                  <div class="banter-loader__box"></div>
                  <div class="banter-loader__box"></div>
                </div>`,
            css: { backgroundColor: "transparent", border: "0" },
            overlayCSS: { opacity: 0.5 },
        });
    }
}),
    (function () {})();
