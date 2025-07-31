"use strict";

$(function () {
    var t,
        s = $(".dt-row-grouping"),
        d =
            s.length &&
            ((t = s.DataTable({
                columns: [
                    { data: "" },
                    { data: "names" },
                    { data: "combo" },
                    { data: "status" },
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
                    {
                        targets: 3,
                        render: function (e, t, a, s) {
                            let statusText = "";

                            switch (a.status) {
                                case "unused":
                                    statusText = "SIN USAR";
                                    break;
                                case "used":
                                    statusText = "USADO";
                                    break;
                                case "active":
                                    statusText = "ACTIVO";
                                    break;
                                default:
                                    statusText =
                                        a.status?.toUpperCase() ??
                                        "DESCONOCIDO";
                            }

                            const isUsed = a.status === "used";
                            const btnClass = isUsed
                                ? "btn-success"
                                : "btn-danger";

                            return `
                            <button
                                class="btn btn-sm ${btnClass} btnValidate"
                                data-id="${a.id}"
                                data-names="${a.names}"
                                data-status="${a.status}"
                                type="button">
                                ${statusText}
                            </button>`;
                        },
                    },
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

    $("#qrCode").change(handleQRCodeChange);

    // Controlador principal del cambio de QR
    function handleQRCodeChange() {
        const code = this.value?.trim();

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
            url: `qr/details/${code}`,
            type: "GET",
        });
    }

    // === RENDERIZADO DE INFORMACIÓN ===
    function renderQRInfo(response) {
        const { data, link } = response;

        updateDataTable(data);
        updateUserInfo(link);
        updateStatusBadge(link.status, link.id);
    }

    function updateDataTable(data) {
        $(".dt-row-grouping").DataTable().clear().rows.add(data).draw();
    }

    function updateUserInfo(link) {
        $("#names").text(link.names);
        $("#documento").text(link.document);
    }

    function updateStatusBadge(status, id) {
        const statusLabels = {
            unused: { text: "SIN USAR", color: "danger" },
            used: { text: "USADO", color: "success" },
        };

        const { text, color } = statusLabels[status] || {
            text: status.toUpperCase(),
            color: "secondary",
        };

        $("#status").html(`
        <button class="btn btn-sm btn-${color}" id ="btnPrint" data-id="${id}">
            <i class="mdi mdi-file-pdf-box me-1"></i> ${text}
        </button>
    `);
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
        updateStatusBadge("");
    }

    t.on("click", ".btnValidate", function () {
        const id = $(this).data("id");
        const name = $(this).data("names");
        const status = $(this).data("status");

        if (status == "used") {
            return false;
        }

        $("#modalName").text(name);

        $("#hiddenRecordId").val(id);

        $("#validateModal").modal("show");
    });

    $("#validateModal").on("shown.bs.modal", function () {
        $("#inputDni").trigger("focus");
    });

    $("#formValidateDni").on("submit", function (e) {
        e.preventDefault();

        const dniIngresado = $("#inputDni").val().trim();

        if (dniIngresado === "") {
            Toast.fire({
                icon: "error",
                title: "El DNI no puede estar vacío.",
            });

            $("#inputDni").focus();
            return;
        }

        $.ajax({
            url: "validate/dni",
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            data: {
                dni: dniIngresado,
            },
            beforeSend: function () {
                blockUI();
            },
            success: function (response) {
                Toast.fire({
                    icon: "success",
                    title: response.message,
                });
                $("#validateModal").modal("hide");
                $("#inputDni").val("");

                const updatedMember = response.data;

                const table = $(".dt-row-grouping").DataTable();

                // Buscar la fila por ID
                const rowIndex = table
                    .rows()
                    .indexes()
                    .filter(function (idx) {
                        return table.row(idx).data().id === updatedMember.id;
                    })[0];

                // Actualizar los datos de la fila
                if (rowIndex !== undefined) {
                    table.row(rowIndex).data(updatedMember).draw(false);
                }
            },
            error: function (xhr) {
                let msg = "Error al validar el DNI.";
                if (xhr.responseJSON?.message) {
                    msg = xhr.responseJSON.message;
                }
                Toast.fire({
                    icon: "error",
                    title: msg,
                });
            },
            complete: function () {
                $.unblockUI();
            },
        });
    });

    $(document).on("click", "#btnPrint", function () {
        const id = $(this).data("id");
        window.open("print?id=" + id, "_blank"); // abre directamente el PDF
    });

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
