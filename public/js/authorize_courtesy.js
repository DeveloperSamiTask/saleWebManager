"use strict";

$(function () {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    var t,
        s = $(".dt-row-grouping"),
        d =
            s.length &&
            ((t = s.DataTable({
                columns: [
                    { data: "" },
                    { data: "names" },
                    { data: "combo" },
                    { data: "document" },
                ],
                columnDefs: [
                    {
                        className: "control",
                        orderable: !1,
                        targets: 0,
                        visible: !1,
                        searchable: !1,
                        render: function (e, t, a, s) {
                            return "";
                        },
                    },
                    {
                        targets: 1,
                        render: function (e, t, a, s) {
                            return `<span class="text-dark text-uppercase">${a.names}</span>`;
                        },
                    },
                    { visible: !1, targets: 2 },
                ],
                order: [[2, "asc"]],
                dom: '<"row"<"col-sm-12 d-flex justify-content-end"f>>t',
                pageLength: -1,
                lengthMenu: [[-1], ["Todos"]],
                language: {
                    processing: "Procesando...",
                    search: "Buscar:",
                    lengthMenu: "Mostrar _MENU_ registros",
                    info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
                    infoEmpty: "Mostrando 0 a 0 de 0 registros",
                    infoFiltered: "(filtrado de _MAX_ registros totales)",
                    infoPostFix: "",
                    loadingRecords: "Cargando...",
                    zeroRecords: "No se encontraron resultados",
                    emptyTable: "No hay datos disponibles en la tabla",
                    paginate: {
                        first: "Primero",
                        previous: "Anterior",
                        next: "Siguiente",
                        last: "Último",
                    },
                    aria: {
                        sortAscending: ": activar para ordenar ascendente",
                        sortDescending: ": activar para ordenar descendente",
                    },
                },
                drawCallback: function (e) {
                    var t = this.api(),
                        a = t.rows({ page: "current" }).nodes(),
                        s = null;
                    t.column(2, { page: "current" })
                        .data()
                        .each(function (e, t) {
                            s !== e &&
                                ($(a)
                                    .eq(t)
                                    .before(
                                        '<tr class="group"><td colspan="8">' +
                                            e +
                                            "</td></tr>"
                                    ),
                                (s = e));
                        });
                },
            })),
            $(".dt-row-grouping tbody").on("click", "tr.group", function () {
                var e = t.order()[0];
                (2 === e[0] && "asc" === e[1]
                    ? t.order([2, "desc"])
                    : t.order([2, "asc"])
                ).draw();
            }));

    // Captura el valor inicial del input al cargar la página
    verifyStatus(code);
    // Controlador principal del cambio de QR
    function handleQRCodeChange(code) {
        if (!code) return;

        blockUI();
        clearAlerts();

        fetchQRDetails(code)
            .done(renderQRInfo)
            .fail(showQRFetchError)
            .always($.unblockUI);
    }

    // === LÓGICA DE FETCH ===
    function fetchQRDetails(code) {
        return $.ajax({
            url: `/PaseCortesia/qr/details/${code}?validate=0`,
            type: "GET",
        });
    }

    // === RENDERIZADO DE INFORMACIÓN ===
    function renderQRInfo(response) {
        const { data, user ,link } = response;

        updateDataTable(data);
        updateUserInfo(link, user);
    }

    function updateDataTable(data) {
        $(".dt-row-grouping").DataTable().clear().rows.add(data).draw();
    }

    function updateUserInfo(link,user) {
        $("#code").text(link.code);
        $("#created_by").text(user)
        $("#d_purchase").text(link.date);
        $("#client").text("TITULAR: " + link.names);
        $("#document").text(link.document);
        $("#date_issue").text("FECHA INGRESO: " + link.date_issue);
    }

    // === GESTIÓN DE ALERTAS ===
    function showQRFetchError(xhr) {
        clearQRData();

        console.error("QR Error:", xhr);

        let message =
            "No se encontró información del QR o hubo un error en el servidor.";

        if (xhr.responseJSON && xhr.responseJSON.message) {
            message = xhr.responseJSON.message;
        }

        showAlert(message);
    }
    function showAlert(message) {
        const alertHtml = `
            <div class="alert alert-solid-danger d-flex align-items-center mt-3" role="alert">
                <i class="mdi mdi-alert-circle-outline me-2"></i>
                ${message}
            </div>
        `;

        $("#alertCard").find(".alert").remove(); // Elimina alertas anteriores dentro de la card
        $("#alertCard").append(alertHtml); // Agrega la nueva alerta al final del card
    }

    function clearAlerts() {
        $("#alertCard").find(".alert").remove();
    }

    function clearQRData() {
        updateDataTable([]);
        updateUserInfo({ names: "", document: "" });
    }

    // === UTILIDADES ===

    $("#btn-approve").on("click", function () {
        Swal.fire({
            title: "¿Estás seguro?",
            text: `Autorizarás el uso del Pase de Cortesía.`,
            icon: "warning",
            input: "text",
            inputPlaceholder: "Ingrese el código de validación",
            showCancelButton: true,
            confirmButtonText: "Sí, aprobar",
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
                    url: "/courtesy/authorize", // 👈 Ajusta la ruta
                    type: "POST",
                    data: {
                        id: code,
                        action: "authorize",
                        code: result.value,
                    },
                })
                    .done((response) => {
                        Swal.fire({
                            text: response.message, // 👈 solo muestra el mensaje
                            icon: "success",
                            confirmButtonText: "Cerrar",
                            customClass: {
                                confirmButton: "btn btn-primary me-3",
                            },
                            buttonsStyling: false,
                        }).then(() => {
                            window.location.href = "about:blank"; // o alguna URL como página de gracias
                        });
                    })
                    .fail((xhr) => {
                        Toast.fire({
                            icon: "error",
                            title:
                                xhr.responseJSON?.message ||
                                "Error en la solicitud",
                        });
                        console.error(xhr.responseText);
                    })
                    .always(() => {
                        $.unblockUI();
                    });
            }
        });
    });

    $("#btn-cancel").on("click", function () {
        Swal.fire({
            title: "¿Anular Pase de Cortesía?",
            text: `Vas a anular el Pase de Cortesía`,
            icon: "warning",
            input: "text",
            inputPlaceholder: "Ingrese el código de autorización",
            showCancelButton: true,
            confirmButtonText: "Sí, anular",
            cancelButtonText: "Volver",
            customClass: {
                confirmButton: "btn btn-danger me-3 waves-effect waves-light",
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
                    url: "/courtesy/authorize",
                    type: "POST",
                    data: {
                        id: code,
                        action: "canceled",
                        code: result.value,
                    },
                })
                    .done((response) => {
                        Swal.fire({
                            text: response.message, // 👈 solo muestra el mensaje
                            icon: "success",
                            confirmButtonText: "Cerrar",
                            customClass: {
                                confirmButton: "btn btn-primary me-3",
                            },
                            buttonsStyling: false,
                        }).then(() => {
                            window.location.href = "about:blank";
                        });
                    })
                    .fail((xhr) => {
                        Toast.fire({
                            icon: "error",
                            title:
                                xhr.responseJSON?.message ||
                                "Error en la solicitud",
                        });
                        console.error(xhr.responseText);
                    })
                    .always(() => {
                        $.unblockUI();
                    });
            }
        });
    });

    function verifyStatus(code) {
        blockUI();

        $.ajax({
            url: `/courtesy/verify`,
            type: "POST",
            data: {
                id: code,
            },
        })
            .done((response) => {
                handleQRCodeChange(response.code);

                if (response.status) {
                    Swal.fire({
                        text: "El pase de cortesía ya ha sido validado el día: " + response.date, // 👈 solo muestra el mensaje
                        icon: "success",
                        confirmButtonText: "Cerrar",
                        customClass: {
                            confirmButton: "btn btn-primary me-3",
                        },
                        buttonsStyling: false,
                    }).then(() => {
                        location.reload(); // o alguna URL como página de gracias
                    });
                } else {
                    handleQRCodeChange(response.code);
                }
            })
            .fail((xhr) => {
                console.error(xhr.responseText);
            })
            .always(() => {
            });
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
});
