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
        s = $(".datatables-promotions"),
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
        onChange: function (selectedDates) {
            if (selectedDates.length === 2) {
                $.blockUI({
                    message:
                        '<div class="sk-wave mx-auto"><div class="sk-rect sk-wave-rect"></div> <div class="sk-rect sk-wave-rect"></div> <div class="sk-rect sk-wave-rect"></div> <div class="sk-rect sk-wave-rect"></div> <div class="sk-rect sk-wave-rect"></div></div>',
                    css: { backgroundColor: "transparent", border: "0" },
                    overlayCSS: { opacity: 0.5 },
                });

                e.ajax.reload();
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
                url: "ShowPromotions",
                data: function (d) {
                    var selectedDates = $("#flatpickr-range").val();
                    if (selectedDates) {
                        var dates = selectedDates.split(" Hasta ");
                        d.startDate = dates[0];
                        d.endDate = dates[1] ?? dates[0];
                    }
                },
            },
            columns: [
                { data: "id" },
                { data: "name" },
                { data: "members" },
                { data: "price" },
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
                    responsivePriority: 1,
                    render: function (t, e, s, n) {
                        var a = s.name,
                            o = s.description;
                        return (
                            '<div class="d-flex justify-content-start align-items-center product-name"><div class="avatar-wrapper me-3"><div class="avatar rounded-2 bg-label-secondary">' +
                            '</div></div><div class="d-flex flex-column"><span class="text-nowrap text-heading fw-medium">' +
                            a +
                            '</span><small class="text-truncate d-none d-sm-block">' +
                            o +
                            "</small></div></div>"
                        );
                    },
                },
                {
                    targets: 2,
                    render: function (e, t, a, n) {
                        return `${e}`;
                    },
                },
                {
                    targets: 3,
                    render: function (e, t, a, n) {
                        return `S/. ${e}`;
                    },
                },
                {
                    targets: -1,
                    responsivePriority: 3,
                    render: function (t, e, n, s) {
                        const checkedAttribute = t == 1 ? "checked" : "";
                        return (
                            '<label class="switch switch-lg"><input type="checkbox" class="switch-input btn-status" ' +
                            checkedAttribute +
                            '><span class="switch-toggle-slider"><span class="switch-on"></span><span class="switch-off"></span></span></label>'
                        );
                    },
                },
            ],
            order: [[3, "desc"]],
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
                    text: '<i class="mdi mdi-plus me-sm-1"></i> <span class="d-none d-sm-inline-block">Agregar Promocion</span>',
                    className:
                        "create-new btn btn-primary waves-effect waves-light",
                    attr: {
                        "data-bs-toggle": "modal",
                        "data-bs-target": "#addNewPromotion",
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
                            return "Details of " + e.data().name;
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

    e.on("click", ".btn-status", function () {
        blockUI();

        let row = $(this).closest("tr");
        let rowData = $(this).closest("table").DataTable().row(row).data(),
            isChecked = $(this).prop("checked");

        let id = rowData.id,
            status = isChecked ? "1" : "0";

        let csrfToken = $('meta[name="csrf-token"]').attr("content");
        let formData = {
            id: id,
            status: status,
            _token: csrfToken, // Agregar el token CSRF aquí
        };

        $.ajax({
            url: "StatusPromotion",
            method: "POST",
            data: formData,
            dataType: "json",
        })
            .done(function (response) {
                if (response.success) {
                    console.log("La categoría se actualizó correctamente.");
                    Toast.fire({
                        icon: response.icon,
                        title: response.message,
                    });
                } else {
                    console.error(
                        "Hubo un error al actualizar la categoría:",
                        response.error
                    );
                }
            })
            .fail(function (xhr, status, error) {
                Toast.fire({
                    icon: error.icon,
                    title: error.message,
                });
                console.error("Hubo un error en la solicitud AJAX:", error);
            })
            .always(function () {
                $.unblockUI();
            });
    });

    e.on("xhr.dt", function () {
        $.unblockUI();
    });

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

    const f = document.getElementById("addNewPromotionForm");

    const fv = FormValidation.formValidation(f, {
        fields: {
            name: {
                validators: {
                    notEmpty: { message: "Ingresa el nombre de la promoción" },
                },
            },
            description: {
                validators: {
                    notEmpty: {
                        message: "Ingresa la descripción de la promoción",
                    },
                },
            },
            price: {
                validators: {
                    notEmpty: { message: "Ingresa el precio" },
                },
            },
            members: {
                validators: {
                    notEmpty: { message: "Ingresa la cantidad" },
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

        if (!f.querySelector('[name="has_food"]').checked) {
            formData.set("has_food", 0);
        }

        fetch("StorePromotion", {
            method: "POST",
            body: formData,
        })
            .then(async (response) => {
                const data = await response.json();

                if (!response.ok) {
                    Toast.fire({
                        icon: data.icon || "error",
                        title:
                            data.message || "Ocurrió un error en el servidor",
                    });

                    if (data.errors) {
                        console.warn("Errores de validación:", data.errors);
                    }

                    throw new Error(data.message || "Error desconocido");
                }

                // Si todo salió bien
                e.ajax.reload();
                Toast.fire({
                    icon: data.icon,
                    title: data.message,
                });
                $("#addNewPromotion").modal("hide");
            })
            .catch((error) => {
                // Captura cualquier error inesperado
                console.error("Error inesperado:", error);
                Toast.fire({
                    icon: "error",
                    title: "Error al guardar: " + error.message,
                });
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
