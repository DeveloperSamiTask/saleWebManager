"use strict";
$(function () {
    let e, n, s;
    s = (
        isDarkStyle
            ? ((e = config.colors_dark.borderColor),
              (n = config.colors_dark.bodyBg),
              config.colors_dark)
            : ((e = config.colors.borderColor),
              (n = config.colors.bodyBg),
              config.colors)
    ).headingColor;
    let t = $(".datatables-categories"),
        o = $(".select2");

    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    const quill = new Quill("#full-editor", {
        bounds: "#full-editor",
        placeholder: "Escribe algo .....",
        modules: {
            formula: !0,
            toolbar: [
                [{ font: [] }, { size: [] }],
                ["bold", "italic", "underline", "strike"],
                [{ color: [] }, { background: [] }],
                [{ script: "super" }, { script: "sub" }],
                [{ header: "1" }, { header: "2" }, "blockquote", "code-block"],
                [
                    { list: "ordered" },
                    { list: "bullet" },
                    { indent: "-1" },
                    { indent: "+1" },
                ],
                [{ direction: "rtl" }],
                [],
                ["clean"],
            ],
        },
        theme: "snow",
    });

    window.quill = quill;

    o.length &&
        o.each(function () {
            var t = $(this);
            select2Focus(t),
                t.wrap('<div class="position-relative"></div>').select2({
                    dropdownParent: t.parent(),
                    placeholder: t.data("placeholder"),
                });
        }),
        t.length &&
            (t.DataTable({
                ajax: "Plantillas/Show",
                columns: [
                    { data: "" },
                    { data: "template_company" },
                    { data: "company" },
                    { data: "promotion" },
                    { data: "" },
                ],
                columnDefs: [
                    {
                        className: "control",
                        searchable: !1,
                        orderable: !1,
                        responsivePriority: 1,
                        targets: 0,
                        render: function (t, e, n, s) {
                            return "";
                        },
                    },
                    {
                        targets: 1,
                        orderable: !1,
                        render: function (t, e, s, n) {
                            return `${t}`;
                        },
                    },
                    {
                        targets: 2,
                        responsivePriority: 2,
                        render: function (t, e, n, s) {
                            return `<span class="h6 ps-0">${t}</span>`;
                        },
                    },
                    {
                        targets: -1,
                        title: "Acciones",
                        searchable: !1,
                        orderable: !1,
                        className: "text-center",
                        render: function (t, e, n, s) {
                            return '<div class="d-flex align-items-sm-center justify-content-sm-center"><button class="btn btn-sm btn-icon datatable_edit"><i class="mdi mdi-pencil-outline"></i></button></div>';
                        },
                    },
                ],
                order: [0, "desc"],
                dom: '<"card-header d-flex rounded-0 flex-wrap py-md-0"<"me-5 ms-n2"f><"d-flex justify-content-start justify-content-md-end align-items-baseline"<"dt-action-buttons d-flex align-items-start align-items-md-center justify-content-sm-center mb-3 mb-sm-0 gap-3"lB>>>t<"row mx-1"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                lengthMenu: [7, 10, 20, 50, 70, 100],
                language: {
                    sLengthMenu: "_MENU_",
                    search: "",
                    searchPlaceholder: "Buscar Categoria",
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
                                    columns: [1, 2],
                                    format: {
                                        body: function (t, e, n) {
                                            var s;
                                            return t.length <= 0
                                                ? t
                                                : ((t = $.parseHTML(t)),
                                                  (s = ""),
                                                  $.each(t, function (t, e) {
                                                      void 0 !== e.classList &&
                                                      e.classList.contains(
                                                          "user-name"
                                                      )
                                                          ? (s +=
                                                                e.lastChild
                                                                    .firstChild
                                                                    .textContent)
                                                          : void 0 ===
                                                            e.innerText
                                                          ? (s += e.textContent)
                                                          : (s += e.innerText);
                                                  }),
                                                  s);
                                        },
                                    },
                                },
                                customize: function (t) {
                                    $(t.document.body)
                                        .css("color", s)
                                        .css("border-color", e)
                                        .css("background-color", n),
                                        $(t.document.body)
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
                                    columns: [1, 2],
                                    format: {
                                        body: function (t, e, n) {
                                            var s;
                                            return t.length <= 0
                                                ? t
                                                : ((t = $.parseHTML(t)),
                                                  (s = ""),
                                                  $.each(t, function (t, e) {
                                                      void 0 !== e.classList &&
                                                      e.classList.contains(
                                                          "user-name"
                                                      )
                                                          ? (s +=
                                                                e.lastChild
                                                                    .firstChild
                                                                    .textContent)
                                                          : void 0 ===
                                                            e.innerText
                                                          ? (s += e.textContent)
                                                          : (s += e.innerText);
                                                  }),
                                                  s);
                                        },
                                    },
                                },
                            },
                            {
                                extend: "excel",
                                text: '<i class="mdi mdi-file-excel-outline me-1"></i>Excel',
                                className: "dropdown-item",
                                exportOptions: {
                                    columns: [1, 2],
                                    format: {
                                        body: function (t, e, n) {
                                            var s;
                                            return t.length <= 0
                                                ? t
                                                : ((t = $.parseHTML(t)),
                                                  (s = ""),
                                                  $.each(t, function (t, e) {
                                                      void 0 !== e.classList &&
                                                      e.classList.contains(
                                                          "user-name"
                                                      )
                                                          ? (s +=
                                                                e.lastChild
                                                                    .firstChild
                                                                    .textContent)
                                                          : void 0 ===
                                                            e.innerText
                                                          ? (s += e.textContent)
                                                          : (s += e.innerText);
                                                  }),
                                                  s);
                                        },
                                    },
                                },
                            },
                            {
                                extend: "pdf",
                                text: '<i class="mdi mdi-file-pdf-box me-1"></i>Pdf',
                                className: "dropdown-item",
                                exportOptions: {
                                    columns: [1, 2],
                                    format: {
                                        body: function (t, e, n) {
                                            var s;
                                            return t.length <= 0
                                                ? t
                                                : ((t = $.parseHTML(t)),
                                                  (s = ""),
                                                  $.each(t, function (t, e) {
                                                      void 0 !== e.classList &&
                                                      e.classList.contains(
                                                          "user-name"
                                                      )
                                                          ? (s +=
                                                                e.lastChild
                                                                    .firstChild
                                                                    .textContent)
                                                          : void 0 ===
                                                            e.innerText
                                                          ? (s += e.textContent)
                                                          : (s += e.innerText);
                                                  }),
                                                  s);
                                        },
                                    },
                                },
                            },
                            {
                                extend: "copy",
                                text: '<i class="mdi mdi-content-copy me-1"></i>Copy',
                                className: "dropdown-item",
                                exportOptions: {
                                    columns: [1, 2],
                                    format: {
                                        body: function (t, e, n) {
                                            var s;
                                            return t.length <= 0
                                                ? t
                                                : ((t = $.parseHTML(t)),
                                                  (s = ""),
                                                  $.each(t, function (t, e) {
                                                      void 0 !== e.classList &&
                                                      e.classList.contains(
                                                          "user-name"
                                                      )
                                                          ? (s +=
                                                                e.lastChild
                                                                    .firstChild
                                                                    .textContent)
                                                          : void 0 ===
                                                            e.innerText
                                                          ? (s += e.textContent)
                                                          : (s += e.innerText);
                                                  }),
                                                  s);
                                        },
                                    },
                                },
                            },
                        ],
                    },
                    {
                        text: '<i class="mdi mdi-plus me-sm-1"></i> <span class="d-none d-sm-inline-block">Agregar Plantilla</span>',
                        className:
                            "add-new btn btn-primary waves-effect waves-light",
                        attr: {
                            "data-bs-toggle": "modal",
                            "data-bs-target": "#addNewTemplate",
                        },
                    },
                ],
                responsive: {
                    details: {
                        display: $.fn.dataTable.Responsive.display.modal({
                            header: function (t) {
                                return "Detalles de " + t.data().categories;
                            },
                        }),
                        type: "column",
                        renderer: function (t, e, n) {
                            n = $.map(n, function (t, e) {
                                return "" !== t.title
                                    ? '<tr data-dt-row="' +
                                          t.rowIndex +
                                          '" data-dt-column="' +
                                          t.columnIndex +
                                          '"><td> ' +
                                          t.title +
                                          ':</td> <td class="ps-0">' +
                                          t.data +
                                          "</td></tr>"
                                    : "";
                            }).join("");
                            return (
                                !!n &&
                                $('<table class="table"/><tbody />').append(n)
                            );
                        },
                    },
                },
            }),
            $(".dataTables_length").addClass("mt-0 mt-md-3"),
            $(".dt-action-buttons").addClass("pt-0")),
        setTimeout(() => {
            $(".dataTables_filter .form-control").removeClass(
                "form-control-sm"
            ),
                $(".dataTables_length .form-select").removeClass(
                    "form-select-sm"
                );
        }, 300);

    $(".add-new").on("click", function () {
        $(".modal-title").text("Agregar Plantilla");

        $(".data-submit").text("Agregar");
    });

    $("#company").on("change", function () {
        let company = $(this).val();
        if (company) {
            $.ajax({
                url: "/Cupon/Promotions",
                type: "post",
                data: { company_id: company },
                beforeSend: function () {
                    blockUI();
                },
            })
                .done((data) => {
                    let $promotion = $("#promotion");
                    $promotion.empty();
                    $promotion.append(
                        `<option value="" selected disabled>Seleccione una promoción</option>`
                    );

                    data.forEach((element) => {
                        $promotion.append(
                            `<option value="${element.id}">${element.name}</option>`
                        );
                    });

                    $promotion
                        .attr("data-loaded", "true")
                        .trigger("promotionsLoaded");
                })
                .fail((response) => {
                    console.error("❌ Error en AJAX:", response.responseText);
                    Toast.fire({
                        icon: "error",
                        title: "Error al cargar promociones. contacte con sistemas.",
                    });
                })
                .always(() => {
                    $.unblockUI();
                });
        }
    });
    t.on("click", ".datatable_edit", function () {
        let row = $(this).closest("tr");
        let rowData = $(this).closest("table").DataTable().row(row).data();

        $("#template_id").val(rowData.id);

        $(".modal-title").text("Editar Plantilla");

        // 🔥 Resetear el select de promociones antes de cargar
        $("#promotion").empty().attr("data-loaded", "false");

        // 🔥 Cambiar la compañía y esperar a que carguen las promociones
        $("#company").val(rowData.company_id).trigger("change");

        $("#template_company").val(rowData.template_company_id).trigger("change");

        // 🔍 Observar cambios en el select de promociones
        let observer = new MutationObserver((mutations, obs) => {
            if ($("#promotion option").length > 1) {
                // Si ya hay opciones cargadas
                $("#promotion").val(rowData.promotion_id).trigger("change");
                $("#promotion").attr("data-loaded", "true");
                obs.disconnect(); // Detener observación
            }
        });

        observer.observe($("#promotion")[0], { childList: true });

        // 🔥 Cargar el contenido del edito
        quill.root.innerHTML = rowData.text;

        $("#addNewTemplate").modal("show");
        $(".data-submit").text("Editar");
    });

    $("#addNewTemplate").on("hidden.bs.modal", function () {
        f.reset();
        fv.resetForm(true);

        $("#company").val(null).trigger("change");
        $("#promotion").empty();
        quill.setContents([]);
    });

    const f = document.getElementById("formTemplate"),
        urlMap = {
            "Agregar Plantilla": "Plantillas/Store",
            "Editar Plantilla": "Plantillas/Update",
        };

    const fv = FormValidation.formValidation(f, {
        fields: {
            template_company: {
                validators: {
                    notEmpty: {
                        message: "Obligatorio seleccionar una empresa para la plantilla",
                    },
                },
            },
            company: {
                validators: {
                    notEmpty: {
                        message: "Obligatorio seleccionar una empresa",
                    },
                },
            },
            promotion: {
                validators: {
                    notEmpty: {
                        message: "Obligatorio seleccionar una promoción",
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
        const method = $(".modal-title").text();
        sendDataServe(urlMap[method]);
    });

    function sendDataServe(url) {
        const submitBtn = document.querySelector(".data-submit");

        // Cambiar el estilo del botón y obtener la función de restablecimiento
        const resetBtn = setLoadingState(submitBtn);

        const formData = new FormData(f);

        formData.append("_token", $('meta[name="csrf-token"]').attr("content"));
        formData.append("content", quill.root.innerHTML);

        fetch(url, {
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
                t.DataTable().ajax.reload();
                Toast.fire({
                    icon: data.icon,
                    title: data.message,
                });
                $("#addNewTemplate").modal("hide");
            })
            .catch((error) => {
                console.error("Error:", error.message);
            })
            .finally(() => {
                resetBtn();
            });
    }

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
        position: "top-end",
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.onmouseenter = Swal.stopTimer;
            toast.onmouseleave = Swal.resumeTimer;
        },
    });
});
