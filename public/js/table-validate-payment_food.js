"use strict";

$(function () {
    const qrStorageKey = "paymentLink.validate.food.qr";

    function setDebugStatus(message, color = "muted") {
        $("#paymentLinkDebug")
            .removeClass("text-muted text-success text-danger text-warning")
            .addClass(`text-${color}`)
            .text(message);
    }

    setDebugStatus("JAVASCRIPT NUEVO CARGADO - frontend comida funcionando", "success");

    var t,
        s = $(".dt-row-grouping"),
        d =
            s.length &&
            ((t = s.DataTable({
                columns: [{ data: "" }, { data: "combo" }, { data: "status" }],
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
                            return `<span class="text-dark text-uppercase">${a.combo}</span>`;
                        },
                    },
                    {
                        targets: -1,
                        render: function (e, t, a, s) {
                            let statusText = "";

                            switch (a.status) {
                                case "unused":
                                    statusText = "SIN USAR";
                                    break;
                                case "partial":
                                    statusText = "PARCIAL";
                                    break;
                                case "used":
                                    statusText = "USADO";
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
                order: [[1, "asc"]],
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
            })),
            $(".dt-row-grouping tbody").on("click", "tr.group", function () {
                var e = t.order()[0];
                (2 === e[0] && "asc" === e[1]
                    ? t.order([2, "desc"])
                    : t.order([2, "asc"])
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
        setDebugStatus("FRONTEND COMIDA OK - consultando QR...", "warning");

        fetchQRDetails(code)
            .done(function (response) {
                renderQRInfo(response);
                setDebugStatus("FRONTEND COMIDA OK - respuesta recibida del backend", "success");
            })
            .fail(function (xhr) {
                showQRFetchError(xhr);
                setDebugStatus(
                    `FRONTEND COMIDA ERROR - backend respondió ${xhr.status || "sin respuesta"}`,
                    "danger"
                );
            })
            .always($.unblockUI);
    }

    // === LÓGICA DE FETCH ===
    function fetchQRDetails(code) {
        return $.ajax({
            url: `qr/details_food/${code}?validate=1`,
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
        if (!id) {
            $("#status").empty();
            return;
        }

        const statusLabels = {
            unused: { text: "SIN USAR", color: "danger" },
            partial: { text: "PARCIAL", color: "warning" },
            used: { text: "USADO", color: "success" },
        };
        const label = statusLabels[status] || {
            text: "DESCONOCIDO",
            color: "secondary",
        };
        const action = status === "used" ? "IMPRIMIR" : "VALIDAR TODO";

        $("#status").html(`
        <button class="btn btn-sm btn-${label.color}" id="btnPrint" data-id="${id}" data-status="${status}">
            <i class="mdi mdi-file-pdf-box me-1"></i> ${label.text}
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

    t.on("click", ".btnValidate", function () {
        let row = $(this).closest("tr");
        let rowData = $(this).closest("table").DataTable().row(row).data();
        const id = rowData.id;
        const name = rowData.combo;
        const quantity = rowData.quantity;
        const validated_qty = rowData.validated_qty;
        const status = rowData.status;

        if (status == "used") {
            return false;
        }

        $("#modalComboName").text(name);
        $("#modalQuantity").html(quantity - validated_qty);

        $("#hiddenRecordId").val(id);
        $("#inputQuantity").val(1);

        $("#validateModal").modal("show");
    });

    $("#validateModal").on("shown.bs.modal", function () {
        $("#inputQuantity").trigger("focus");
    });

    $("#formValidateCombo").on("submit", function (e) {
        e.preventDefault();

        const quantity = $("#inputQuantity").val().trim();

        if (quantity === "" || quantity <= 0) {
            Toast.fire({
                icon: "error",
                title: "La cantidad es obligatoria y debe ser mayor a 0.",
            });
            $("#inputQuantity").focus();
            return;
        }

        $.ajax({
            url: "validate/combos",
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            data: {
                record_id: $("#hiddenRecordId").val(),
                quantity: quantity,
            },
            beforeSend: function () {
                blockUI();
                $("#btnValidateCombo").prop("disabled", true);
            },
            success: function (response) {
                Toast.fire({
                    icon: "success",
                    title: `${response.message} Cantidad: ${quantity}`,
                });

                $("#validateModal").modal("hide");
                $("#inputQuantity").val("");

                const updatedCombo = response.data;
                const table = $(".dt-row-grouping").DataTable();

                // Buscar la fila por ID
                const rowIndex = table
                    .rows()
                    .indexes()
                    .filter(function (idx) {
                        return table.row(idx).data().id === updatedCombo.id;
                    })[0];

                // Actualizar los datos de la fila
                if (rowIndex !== undefined) {
                    table.row(rowIndex).data(updatedCombo).draw(false);

                    // Aplicar clase de resaltado
                    const rowNode = table.row(rowIndex).node();
                    $(rowNode).addClass("table-success");
                }
            },
            error: function (xhr) {
                let msg = "Error al validar el combo.";
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
                $("#btnValidateCombo").prop("disabled", false);
            },
        });
    });

    $(document).on("click", "#btnPrint", function () {
        const id = $(this).data("id");
        const status = $(this).data("status");

        if (status !== "used") {
            const printWindow = window.open("about:blank", "_blank");

            $.ajax({
                url: "validate/all-food",
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
                        row.validated_qty = row.quantity;
                        this.data(row);
                    });
                    table.draw(false);

                    Toast.fire({ icon: "success", title: response.message });
                    if (printWindow) {
                        printWindow.location = "printFood?id=" + id;
                    } else {
                        window.open("printFood?id=" + id, "_blank");
                    }
                    setTimeout(function () {
                        location.reload();
                    }, 1000);
                },
                error: function (xhr) {
                    if (printWindow) printWindow.close();
                    Toast.fire({
                        icon: "error",
                        title: xhr.responseJSON?.message || "No se pudieron validar los combos.",
                    });
                },
                complete: $.unblockUI,
            });
            return;
        }

        window.open("printFood?id=" + id, "_blank");

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
