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
        invoice = {
            1: {
                title: "PENDIENTE",
                class: "badge rounded-pill bg-label-warning",
            },
            2: {
                title: "ACEPTADO",
                class: "badge rounded-pill bg-label-success",
            },
            3: {
                title: "ANULADO",
                class: "badge rounded-pill bg-label-danger",
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

                console.log(isChecked);

                var startDate = selectedDates[0].toISOString();
                var endDate = selectedDates[1].toISOString();
                $.ajax({
                    url: "coupons_table",
                    type: "GET",
                    data: {
                        start_date: startDate,
                        end_date: endDate,
                        isChecked: isChecked,
                    },
                })
                    .done((response) => {
                        e.clear().rows.add(response.data).draw();
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
            ajax: "coupons_table",
            columns: [
                { data: "id" },
                { data: "description" },
                { data: "names" },
                { data: "user_send" },
                { data: "date_use" },
                { data: "file" },
                { data: "status" },
                { data: "date_insert" },
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
                    targets: 5,
                    className: "text-center",
                    render: function (a) {
                        if (a == "") {
                            return `<div class="button-wrapper">
                                        <label for="upload" class="btn btn-primary me-2 mb-3 waves-effect waves-light" tabindex="0">
                                            <span class="d-none d-sm-block"><i class="mdi mdi-cloud-upload-outline"></i> Subir </span>
                                            <i class="mdi mdi-tray-arrow-up d-block d-sm-none"></i>
                                            <input type="file" id="upload" class="account-file-input" hidden="" accept="image/png, image/jpeg, image/jpg">
                                        </label>
                                    </div>`;
                        } else {
                            return `
                                <div class="button-wrapper">
                                    <button type="button" class="btn btn-info btn-sm view-image-btn" data-bs-toggle="modal" data-bs-target="#imagePreviewModal" data-image="/files/${a}">
                                        <i class="mdi mdi-eye"></i> Ver Imagen
                                    </button>
                                </div>
                            `;
                        }
                    },
                },
                {
                    targets: 6,
                    render: function (a, e, t, s) {
                        return (
                            '<span class="' +
                            invoice[a].class +
                            '" text-capitalized="">' +
                            invoice[a].title +
                            "</span>"
                        );
                    },
                },

                {
                    targets: -1,
                    render: function (a, e, t, s) {
                        return a;
                    },
                },
            ],
            drawCallback: function () {
                $(".account-file-input")
                    .off("change")
                    .on("change", function (event) {
                        blockUI();
                        const file = event.target.files[0];
                        if (file) {
                            const $row = $(this).closest("tr"); // Encuentra la fila de la tabla
                            const rowData = e.row($row).data(); // Obtiene los datos de esa fila
                            const id = rowData.id; // Extrae el ID de los datos de la fila

                            const formData = new FormData();
                            formData.append("file", file);
                            formData.append("id", id); // Agrega el ID al FormData

                            fetch("/uploadCoupon", {
                                method: "POST",
                                body: formData,
                                headers: {
                                    "X-CSRF-TOKEN": $(
                                        'meta[name="csrf-token"]'
                                    ).attr("content"),
                                },
                            })
                                .then((response) => response.json())
                                .then((data) => {
                                    e.ajax.reload();
                                    if (data.icon == "success") {
                                        Toast.fire({
                                            icon: data.icon,
                                            title: data.message,
                                        });
                                    } else {
                                        alert("Error al subir la imagen");
                                    }
                                })
                                .catch((error) => {
                                    console.error("Error:", error);
                                    alert("Error al subir la imagen");
                                })
                                .finally(() => {
                                    $.unblockUI();
                                });
                        }
                    });
            },
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
                    text: '<i class="mdi mdi-export-variant me-1"></i> <span class="d-none d-sm-inline-block">Export</span>',
                    buttons: [
                        {
                            extend: "print",
                            text: '<i class="mdi mdi-printer-outline me-1" ></i>Print',
                            className: "dropdown-item",
                            exportOptions: {
                                columns: [1, 2, 3, 4, 6, 7],
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
                                columns: [1, 2, 3, 4, 6, 7],
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
                                columns: [1, 2, 3, 4, 6, 7],
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
                                columns: [1, 2, 3, 4, 6, 7],
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
                                columns: [1, 2, 3, 4, 6, 7],
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
                    text: '<i class="mdi mdi-plus me-sm-1"></i> <span class="d-none d-sm-inline-block">Agregar Cupon</span>',
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

    const f = document.getElementById("addNewCouponForm");

    const fv = FormValidation.formValidation(f, {
        fields: {
            names: {
                validators: {
                    notEmpty: { message: "Ingresa nombres y apellidos" },
                },
            },
            description: {
                validators: {
                    notEmpty: { message: "Ingresa la descripción del cupón" },
                },
            },
            document: {
                validators: {
                    notEmpty: { message: "Ingresa el documento de identidad" },
                },
            },
            date_use: {
                validators: {
                    notEmpty: { message: "Ingresa la fecha de uso" },
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

    $(document).on("click", ".view-image-btn", function () {
        const imageUrl = $(this).data("image");
        $("#imagePreview").attr("src", imageUrl);
    });

    fv.on("core.form.valid", function () {
        blockUI();

        let formData = new FormData(f);
        formData.append("_token", csrfToken);

        fetch("insertCoupon", {
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
            })
            .finally(() => {
                $.unblockUI();
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
