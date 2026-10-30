"use strict";

$(function () {
    const qrStorageKey = "paymentLink.validate.qr";

    function setDebugStatus(message, color = "muted") {
        $("#paymentLinkDebug")
            .removeClass("text-muted text-success text-danger text-warning")
            .addClass(`text-${color}`)
            .text(message);
    }

    setDebugStatus("JAVASCRIPT NUEVO CARGADO - frontend funcionando", "success");

    var t,
        s = $(".dt-row-grouping"),
        d =
            s.length &&
            ((t = s.DataTable({
                columns: [
                    { data: "" },
                    { data: "names" },
                    { data: "document" },
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
                    {
                        targets: 2,
                        render: function (e, t, a) {
                            return `<span class="text-dark">${a.document ?? a.dni ?? ""}</span>`;
                        },
                    },
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
                                class="btn btn-sm ${btnClass} memberStatus"
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
                rowCallback: function (row, data) {
                    $(row)
                        .toggleClass("table-success", data.status === "used")
                        .toggleClass("table-danger", data.status !== "used");
                },
                drawCallback: function () {
                    const table = this.api();
                    const rows = table.rows({ page: "current" }).nodes();
                    let lastCombo = null;
                    let position = 0;

                    table.rows({ page: "current" }).every(function () {
                        const rowData = this.data();
                        const combo = rowData?.combo || "";

                        if (lastCombo !== combo) {
                            $(rows)
                                .eq(position)
                                .before(
                                    '<tr class="group"><td colspan="8">' +
                                        combo +
                                        "</td></tr>"
                                );
                            lastCombo = combo;
                        }

                        position++;
                    });
                },
            })),
            $(".dt-row-grouping tbody").on("click", "tr.group", function () {
                var e = t.order()[0];
                (1 === e[0] && "asc" === e[1]
                    ? t.order([1, "desc"])
                    : t.order([1, "asc"])
                ).draw();
            }));

    $("#qrCode").change(handleQRCodeChange);

    const savedQrCode = sessionStorage.getItem(qrStorageKey);
    if (savedQrCode) {
        $("#qrCode").val(savedQrCode);
        handleQRCodeChange.call($("#qrCode")[0]);
    }

    // Controlador principal del cambio de QR
    function handleQRCodeChange() {
        const code = this.value?.trim();

        if (!code) return;

        sessionStorage.setItem(qrStorageKey, code);

        blockUI();
        clearAlerts();
        setDebugStatus("FRONTEND OK - consultando QR...", "warning");

        fetchQRDetails(code)
            .done(function (response) {
                renderQRInfo(response);
                setDebugStatus("FRONTEND OK - respuesta recibida del backend", "success");
            })
            .fail(function (xhr) {
                showQRFetchError(xhr);
                setDebugStatus(
                    `FRONTEND ERROR - backend respondió ${xhr.status || "sin respuesta"}`,
                    "danger"
                );
            })
            .always($.unblockUI);
    }

    // === LÓGICA DE FETCH ===
    function fetchQRDetails(code) {
        return $.ajax({
            url: `qr/details/${code}?validate=1`,
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
        const normalizedData = (data || []).map((member) => ({
            ...member,
            document: member.document ?? member.dni ?? "",
        }));

        $(".dt-row-grouping").DataTable().clear().rows.add(normalizedData).draw();
    }

    function updateUserInfo(link) {
        $("#names").text(link.names);
        $("#documento").text(link.document);
    }

    function updateStatusBadge(status, id) {
        if (!id) {
            $("#status").empty();
            return;
        }

        const statusLabels = {
            unused: { text: "SIN USAR", color: "danger" },
            used: { text: "USADO", color: "success" },
        };

        const { text, color } = statusLabels[status] || {
            text: status.toUpperCase(),
            color: "secondary",
        };

        const action = status === "unused" ? "VALIDAR TODO" : "IMPRIMIR";

        $("#status").html(`
        <button class="btn btn-sm btn-${color}" id="btnPrint" data-id="${id}" data-status="${status}">
            <i class="mdi mdi-file-pdf-box me-1"></i> ${text}
        </button>
        <small class="d-block mt-1 text-muted">${action}</small>
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

    // El estado de cada persona abre el modal con el DNI cargado desde la BD.
    t.on("click", ".memberStatus", function () {
        const rowData = t.row($(this).closest("tr")).data();

        if (!rowData) return;

        $("#modalName").text(rowData.names || "");
        $("#inputDni").val(rowData.document ?? rowData.dni ?? "");
        $("#inputDni").prop("readonly", rowData.status === "used");
        $("#btnValidateDni").toggle(rowData.status !== "used");
        $("#hiddenRecordId").val(rowData.id || "");
        $("#validateModal").modal("show");
    });

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
                id: $("#hiddenRecordId").val(),
                dni: dniIngresado,
            },
            beforeSend: function () {
                blockUI();
                $("#btnValidateDni").prop("disabled", true);
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

                    // Obtener el nodo de la fila ya actualizada
                    const rowNode = table.row(rowIndex).node();

                    // Aplicar clase de resaltado
                    $(rowNode).addClass("table-success");


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
                $("#btnValidateDni").prop("disabled", false);
            },
        });
    });

    $(document).on("click", "#btnPrint", function () {
        const id = $(this).data("id");
        const status = $(this).data("status");

        if (status === "unused") {
            const printWindow = window.open("about:blank", "_blank");

            $.ajax({
                url: "validate/all",
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
                data: { id },
                beforeSend: blockUI,
                success: function (response) {
                    updateStatusBadge("used", id);

                    const table = $(".dt-row-grouping").DataTable();
                    table.rows().every(function () {
                        const row = this.data();
                        row.status = "used";
                        this.data(row);
                    });
                    table.draw(false);

                    Toast.fire({ icon: "success", title: response.message });
                    if (printWindow) {
                        printWindow.location = "print?id=" + id;
                    } else {
                        window.open("print?id=" + id, "_blank");
                    }
                    setTimeout(function () {
                        location.reload();
                    }, 1000);
                },
                error: function (xhr) {
                    if (printWindow) printWindow.close();
                    Toast.fire({
                        icon: "error",
                        title: xhr.responseJSON?.message || "No se pudieron validar las entradas.",
                    });
                },
                complete: $.unblockUI,
            });
            return;
        }

        window.open("print?id=" + id, "_blank");

        setTimeout(function () {
            location.reload();
        }, 2000);
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
