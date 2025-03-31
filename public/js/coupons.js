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
    let today = new Date();
    let formattedToday =
            today.getDate().toString().padStart(2, "0") +
            "-" +
            (today.getMonth() + 1).toString().padStart(2, "0") +
            "-" +
            today.getFullYear(),
        csrfToken = $('meta[name="csrf-token"]').attr("content");

    t = $(".select2");
    t.length &&
        t.each(function () {
            var e = $(this);
            select2Focus(e),
                e.wrap('<div class="position-relative"></div>').select2({
                    placeholder: e.data("placeholder"),
                    dropdownParent: e.parent(),
                });
        });

    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

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
                    url: "/Cupon/Show",
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

    let datePicker = $("#expired_date").flatpickr({
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

    // Calculate the expiration date (one year minus one day)
    let expirationDate = new Date();
    expirationDate.setFullYear(today.getFullYear() + 1); // Add one year
    expirationDate.setDate(today.getDate() - 1); // Subtract one day

    // Format the date as "YYYY-MM-DD"
    let day = String(expirationDate.getDate()).padStart(2, "0"); // Ensure two digits
    let month = String(expirationDate.getMonth() + 1).padStart(2, "0"); // Ensure two digits (months are 0-based)
    let year = expirationDate.getFullYear();
    let formattedDate = `${day}-${month}-${year}`;

    s.length &&
        (e = s.DataTable({
            ajax: {
                url: "/Cupon/Show",
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
                { data: "code" },
                { data: "company" },
                { data: "client" },
                { data: "document" },
                { data: "issue_date" },
                { data: "issue_date" },
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
                        return `<a href="Cupon/pdf/${e}" target="_blank">${e}</a>`;
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
                    targets: [5, 6],
                    className: "text-center",
                    render: function (a) {
                        if (!a) return "";
                        let date = new Date(a);
                        let day = date.getDate().toString().padStart(2, "0");
                        let month = (date.getMonth() + 1)
                            .toString()
                            .padStart(2, "0");
                        let year = date.getFullYear();
                        return `${day}/${month}/${year}`;
                    },
                },
                {
                    targets: 7,
                    title: "Estado",
                    render: function (data, type, row) {
                        let statusLabels = {
                            0: { text: "ACTIVO", class: "success" },
                            1: { text: "USADO", class: "primary" },
                            2: { text: "INACTIVO", class: "danger" },
                            3: { text: "VENCIDO", class: "warning" },
                        };

                        let label = `<span class="badge bg-${statusLabels[data].class}">${statusLabels[data].text}</span>`;

                        // Solo mostrar opciones de activación/inactivación si es 0 o 2
                        if (data === 0 || data === 2) {
                            let newStatus = data === 0 ? 2 : 0;
                            let newText = data === 0 ? "desactivar" : "Activar";
                            let newClass = data === 0 ? "danger" : "success";

                            return `
                                <div class="btn-group">
                                    ${label}
                                    <button type="button" class="btn btn-${statusLabels[data].class} btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-cog"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a class="dropdown-item change-status" href="javascript:void(0);"
                                               data-id="${row.id}" data-status="${newStatus}">
                                                <i class="fas fa-sync-alt text-${newClass}"></i> ${newText}
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            `;
                        }

                        return label;
                    },
                },
                {
                    targets: -1,
                    title: "Fecha Uso",
                    render: function (a, e, t, s) {
                        if (!a) return "";
                        let date = new Date(a);
                        let day = date.getDate().toString().padStart(2, "0");
                        let month = (date.getMonth() + 1)
                            .toString()
                            .padStart(2, "0");
                        let year = date.getFullYear();
                        return `${day}/${month}/${year}`;
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
                    text: '<i class="mdi mdi-plus me-sm-1"></i> <span class="d-none d-sm-inline-block">Crear Cupon</span>',
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

    $("#doc").on("input", function () {
        let docValue = $(this).val().trim();

        if (
            $("#collapsible-address-type-home").prop("checked") &&
            docValue.length === 8
        ) {
            fetchDNI(docValue);
        }
    });

    $("#company").on("change", function () {
        let company = $(this).val();
        if (company) {
            $.ajax({
                url: "/Cupon/Promotions",
                type: "post",
                data: {
                    company_id: company,
                },
                beforeSend: function () {
                    blockUI();
                },
            })
                .done((data) => {
                    console.log(data);

                    $("#promotion").empty();
                    $("#promotion").append(
                        `<option value="" selected disabled>Seleccione una promoción</option>`
                    );
                    data.forEach((element) => {
                        $("#promotion").append(
                            `<option value="${element.id}">${element.name}</option>`
                        );
                    });
                })
                .fail((response) => {
                    console.log(response.responseText);
                })
                .always(() => {
                    $.unblockUI();
                });
        }
    });

    e.on("click", ".change-status", function () {
        let id = $(this).data("id");
        let newStatus = $(this).data("status");
        blockUI();
        $.ajax({
            url: "/Cupon/Status",
            method: "POST",
            data: {
                id: id,
                status: newStatus,
            },
            success: function (response) {
                e.ajax.reload();

                Toast.fire({
                    icon: response.icon,
                    title: response.message,
                });

                $.unblockUI();
            },
            error: function () {
                Toast.fire({
                    icon: response.icon,
                    title: response.message,
                });
            },
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
            expired_date: {
                validators: {
                    notEmpty: { message: "Ingresa la fecha de nacimiento" },
                },
            },
            company: {
                validators: {
                    notEmpty: { message: "Selecciona la empresa" },
                },
            },

            promotion: {
                validators: {
                    notEmpty: { message: "Selecciona la promoción" },
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

        fetch("/Cupon/Create", {
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

    $("#addNewCoupon").on("hidden.bs.modal", function () {
        $("#partnerForm")[0].reset();
        fv.resetForm(true);

        datePicker.setDate(formattedToday, true);
        $("#company").val(null).trigger("change");
        $("#promotion").val(null).trigger("change");
    });

    function fetchDNI(dni) {
        $.ajax({
            url: "/Cupon/DNI",
            type: "POST",
            data: { dni: dni },
            dataType: "json",
            beforeSend: function () {
                $("#doc").prop("disabled", true);
                $("#doc").after(
                    '<span id="loading" class="text-primary ms-2">Buscando...</span>'
                );
            },
        })
            .done((data) => {
                if (data.success) {
                    $("#pattername").val(data.last_name);
                    $("#mattername").val(data.mother_last_name);
                    $("#names").val(data.first_name);
                } else {
                    Toast.fire({
                        icon: "error",
                        title: "❌ DNI no encontrado",
                    });
                }
            })
            .fail((jqXHR, textStatus, errorThrown) => {
                console.log(
                    "⚠️ Error en la petición:",
                    textStatus,
                    errorThrown,
                    jqXHR.responseText
                );
                Toast.fire({
                    icon: "error",
                    title: "❌ Error en la petición",
                });
            })
            .always(() => {
                $("#doc").prop("disabled", false);
                $("#loading").remove();
            });
    }

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
