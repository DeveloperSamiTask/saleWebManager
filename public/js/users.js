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

    var e,
        s = $(".datatables-entries"),
        i = $(".select2"),
        status = {
            ACTIVO: {
                title: "ACTIVO",
                class: "badge rounded-pill bg-label-success",
            },
            INACTIVO: {
                title: "INACTIVO",
                class: "badge rounded-pill bg-label-danger",
            },
        };

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
                ajax: "/Usuarios/Show",
                columns: [
                    { data: "id" },
                    { data: "name" },
                    { data: "rol" },
                    { data: "companies" },
                    { data: "status" },
                    { data: "" },
                ],
                columnDefs: [
                    {
                        className: "control",
                        responsivePriority: 2,
                        targets: 0,
                        render: function (e, t, a, n) {
                            return e;
                        },
                    },
                    {
                        targets: 1,
                        render: function (e, t, a, n) {
                            return `<span>${e}</span>`;
                        },
                    },
                    {
                        targets: 2,
                        render: function (e, t, a, n) {
                            return `<span>${e}</span>`;
                        },
                    },
                    {
                        targets: 3,
                        render: function (e, t, a, n) {
                            return `<span>${e}</span>`;
                        },
                    },
                    {
                        targets: 4,
                        render: function (e, t, a, n) {
                            return (
                                '<span class="' +
                                status[e].class +
                                '" text-capitalized="">' +
                                status[e].title +
                                "</span>"
                            );
                        },
                    },
                    {
                        targets: -1,
                        title: "Acciones",
                        orderable: !1,
                        className: "text-center",
                        render: function (e, t, a, n) {
                            return ` <a href="javascript:;" data-bs-toggle="tooltip" class="text-body updated-record" data-bs-placement="top" title="Delete Invoice">
                                <i class="mdi mdi-account-edit-outline mdi-24px mx-1"></i>
                            </a>`;
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
                                    columns: [1, 2, 3],
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
                                    columns: [1, 2, 3],
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
                                    columns: [1, 2, 3],
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
                                    columns: [1, 2, 3],
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
                                    columns: [1, 2, 3],
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
                    {
                        text: '<i class="mdi mdi-plus me-sm-1"></i> <span class="d-none d-sm-inline-block add-record">Crear Usuario</span>',
                        className:
                            "create-new btn btn-primary waves-effect waves-light",
                        attr: {
                            "data-bs-toggle": "offcanvas",
                            "data-bs-target": "#offcanvasUser    ",
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
                initComplete: function () {
                    this.api()
                        .columns(2)
                        .every(function () {
                            var t = this,
                                a = $(
                                    '<select id="UserRole" class="select2 form-select text-capitalize"><option value=""> Seleccionar Rol </option></select>'
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
                            .columns(4)
                            .every(function () {
                                var t = this,
                                    a = $(
                                        '<select id="UserPlan" class="select2 form-select text-capitalize"><option value=""> Seleccionar Estado</option></select>'
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
                                                status[e].title +
                                                '">' +
                                                status[e].title +
                                                "</option>"
                                        );
                                    });
                            });
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

    $(".add-record").on("click", function () {
        $(".offcanvas-title").text("Crear Usuario");
    });

    e.on("click", ".updated-record", function () {
        let t = $(this).closest("tr");
        let a = e.row(t).data();
        let userId = a.id;
        let userName = a.name;
        let userRole = a.idrol;
        let userCompanies = a.idcompanies;

        $(".offcanvas-title").text("Actualizar Usuario");

        $("#offcanvasUser").offcanvas("show");
        $("#user_id").val(userId);
        $("#user_name").val(userName);
        $("#user_role").val(userRole).change();
        $("#user_companies").val(userCompanies.split(",")).change();
    });

    const f = document.getElementById("formUser"),
        urlMap = {
            "Crear Usuario": "Create",
            "Actualizar Usuario": "Update",
        };

    const fv = FormValidation.formValidation(f, {
        fields: {
            user_name: {
                validators: {
                    notEmpty: {
                        message: "Ingresa el nombre de usuario",
                    },
                },
            },
            user_role: {
                validators: {
                    notEmpty: {
                        message: "Selecciona el rol del usuario",
                    },
                },
            },
            user_companies: {
                validators: {
                    notEmpty: {
                        message:
                            "Selecciona las empresas a la que pertenecera el usuario",
                    },
                },
            },
        },
        plugins: {
            trigger: new FormValidation.plugins.Trigger(),
            bootstrap5: new FormValidation.plugins.Bootstrap5({
                eleValidClass: "is-valid",
                rowSelector: function (t, e) {
                    return ".mb-4";
                },
            }),
            submitButton: new FormValidation.plugins.SubmitButton(),
            autoFocus: new FormValidation.plugins.AutoFocus(),
        },
    });

    fv.on("core.form.valid", function () {
        const method = $(".offcanvas-title").text();
        sendDataServe(urlMap[method]);
    });

    function sendDataServe(url) {
        const submitBtn = document.querySelector(".data-submit");

        // Cambiar el estilo del botón y obtener la función de restablecimiento
        const resetBtn = setLoadingState(submitBtn);

        const formData = new FormData(f);

        formData.append("_token", $('meta[name="csrf-token"]').attr("content"));

        fetch(`Usuarios/${url}`, {
            method: "POST",
            body: formData,
        })
            .then((response) => {
                if (!response.ok) {
                    throw new Error(
                        "Hubo un problema al procesar el formulario."
                    );
                }
                return response.json();
            })
            .then((data) => {
                e.ajax.reload();
                Toast.fire({
                    icon: data.icon,
                    title: data.message,
                });
                $("#offcanvasUser").offcanvas("hide");
            })
            .catch((error) => {
                console.error("Error:", error.message);
            })
            .finally(() => {
                resetBtn();
            });
    }

    $("#offcanvasUser").on("hidden.bs.offcanvas", function () {
        $("#formUser")[0].reset();
        $("#user_role").val(null).trigger("change");
        $("#user_companies").val(null).trigger("change");
    });

    // Función para cambiar el estilo del botón durante la carga y deshabilitarlo
    function setLoadingState(btnElement) {
        // Guardar el estado original del botón
        const originalContent = btnElement.innerHTML;
        const originalDisabled = btnElement.disabled;

        // Cambiar el contenido del botón a un spinner y "Loading..."
        btnElement.innerHTML =
            '<span class="spinner-border me-1" role="status" aria-hidden="true"></span>Cargando ...';
        btnElement.disabled = true;

        // Devolver la función para restaurar el contenido original del botón y habilitarlo nuevamente
        return function resetBtn() {
            btnElement.innerHTML = originalContent;
            btnElement.disabled = originalDisabled;
        };
    }

    const Toast = Swal.mixin({
        toast: true,
        position: "top-end",
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.onmouseenter = Swal.stopTimer;
            toast.onmouseleave = Swal.resumeTimer;
        },
    });
}),
    (function () {})();
