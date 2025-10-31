$(() => {
    var t, a, s;
    entriesAmount();
    var status = {
        1: {
            title: "ACTIVO",
            class: "badge rounded-pill bg-label-success",
        },
        0: {
            title: "DESACTIVADO",
            class: "badge rounded-pill bg-label-danger",
        },
    };
    const used = {
        0: { title: "SIN USAR", class: "badge rounded-pill bg-label-danger" },
        1: { title: "USADO", class: "badge rounded-pill bg-label-success" },
    };
    s = (
        isDarkStyle
            ? ((t = config.colors_dark.borderColor),
              (a = config.colors_dark.bodyBg),
              config.colors_dark)
            : ((t = config.colors.borderColor),
              (r = config.colors.headingColor),
              (a = config.colors.bodyBg),
              config.colors)
    ).headingColor;
    moment.locale("es");
    var e,
        t = $(".datatables-permissions"),
        csrfToken = $('meta[name="csrf-token"]').attr("content"),
        s;
    t.length &&
        (e = t.DataTable({
            columns: [
                { data: "" },
                { data: "id" },
                { data: "id" },
                { data: "document" },
                { data: "name" },
                { data: "product" },
                { data: "price" },
                { data: "purchase" },
                { data: "sure" },
                { data: "status" },
            ],
            columnDefs: [
                {
                    className: "control",
                    orderable: !1,
                    searchable: !1,
                    responsivePriority: 2,
                    targets: 0,
                    render: function (e, t, a, n) {
                        return "";
                    },
                },
                {
                    targets: 1,
                    orderable: !1,
                    render: function () {
                        return '<input type="checkbox" class="dt-checkboxes form-check-input">';
                    },
                    checkboxes: {
                        selectAllRender:
                            '<input type="checkbox" class="form-check-input">',
                    },
                },
                { targets: 2, searchable: !1 },

                {
                    targets: 3,
                    responsivePriority: 4,
                    render: function (a, e, t, s) {
                        return (
                            "<h6 class='text-truncate d-flex align-items-center mb-0'>" +
                            a +
                            "</h6>"
                        );
                    },
                },
                {
                    targets: 4,
                    responsivePriority: 5,
                    render: function (a, e, t, s) {
                        var r = Math.floor(11 * Math.random()) + 1,
                            l;
                        return (
                            '<div class="d-flex justify-content-start align-items-center"><div class="avatar-wrapper"><div class="avatar avatar-sm me-2">' +
                            '<span class="avatar-initial rounded-circle bg-label-' +
                            [
                                "success",
                                "danger",
                                "warning",
                                "info",
                                "dark",
                                "primary",
                                "secondary",
                            ][Math.floor(6 * Math.random())] +
                            '">' +
                            (l =
                                ((l = a.match(/\b\w/g) || []).shift() || "") +
                                (l.pop() || "")).toUpperCase() +
                            "</span></div></div><div class='d-flex flex-column gap-1'><a href='javascript:void(0)' class'truncate'><h6 class='mb-0'>" +
                            a +
                            "</h6></a></div></div>"
                        );
                    },
                },
                {
                    target: 5,
                    render: function (a) {
                        return `<h6 class="">${String(a).toUpperCase()}</h6>`;
                    },
                },
                {
                    targets: 8,
                    render: function (a) {
                        return (
                            '<span class="' +
                            status[a].class +
                            '" text-capitalized="">' +
                            status[a].title +
                            "</span>"
                        );
                    },
                },
                {
                    targets: -1,
                    class: "text-center",
                    orderable: !1,
                    render: function (e, t, a, n) {
                        return (
                            '<span class="' +
                            used[e].class +
                            '" text-capitalized="">' +
                            used[e].title +
                            "</span>"
                        );
                    },
                },
            ],
            language: {
                emptyTable: "No hay entradas disponibles en la tabla",
                info: "Mostrando _START_ a _END_ de _TOTAL_ entradas",
                infoEmpty: "Mostrando 0 a 0 de 0 entradas",
                infoFiltered: "(filtrado de _MAX_ entradas totales)",
                lengthMenu: "Mostrar _MENU_ entradas",
                loadingRecords: "Cargando...",
                processing: "Procesando...",
                search: "Buscar:",
                zeroRecords: "No se encontraron registros coincidentes",
                paginate: {
                    first: "Primero",
                    last: "Último",
                    next: "Siguiente",
                    previous: "Anterior",
                },
            },
            order: [[2, "asc"]],
            searching: false,
            dom: '<"row mx-1"<"col-sm-12 col-md-3" l><"col-sm-12 col-md-9"<"dt-action-buttons text-xl-end text-lg-start text-md-end text-start d-flex align-items-center justify-content-md-end justify-content-center flex-wrap me-1"<"me-3"f>B>>>t<"row mx-2"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
            displayLength: 10,
            lengthMenu: [10, 25, 50, 75, 100],
            buttons: [
                {
                    text: "Limpiar",
                    className:
                        "clear-data btn rounded-pill btn-danger waves-effect waves-light mt-3",
                },
                {
                    text: "Enviar",
                    className:
                        "send-data btn rounded-pill btn-primary waves-effect waves-light mt-3",
                },
            ],
            responsive: {
                details: {
                    display: $.fn.dataTable.Responsive.display.modal({
                        header: function (e) {
                            return e.data().name;
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
        $(".datatables-permissions tbody").on(
            "click",
            ".delete-record",
            function () {
                e.row($(this).parents("tr")).remove().draw();
            }
        );

    $("#addPermissionModal").on("shown.bs.modal", function () {
        $("#ticket").focus();
    });

    $("#ticket").on("input", function () {
        var ticket = $(this).val().replace(/\s+/g, "");
        var ticketExists = false;

        if (ticket.length > 5) {
            $(".datatables-permissions tbody tr").each(function () {
                var ticketEnTabla = $(this)
                    .find("td:eq(1)") // ✅ Columna 1 = ID
                    .text()
                    .replace(/\s+/g, "");
                if (ticketEnTabla === ticket) {
                    ticketExists = true;
                    return false;
                }
            });

            if (ticketExists) {
                Toast.fire({
                    icon: "error",
                    title: "La Entrada ya se encuentra en la lista",
                });
                playErrorSound();
            } else {
                $.blockUI({
                    message:
                        '<div class="sk-wave mx-auto"><div class="sk-rect sk-wave-rect"></div> <div class="sk-rect sk-wave-rect"></div> <div class="sk-rect sk-wave-rect"></div> <div class="sk-rect sk-wave-rect"></div> <div class="sk-rect sk-wave-rect"></div></div>',
                    css: { backgroundColor: "transparent", border: "0" },
                    overlayCSS: { opacity: 0.5 },
                });

                $.ajax({
                    url: "viewFDTTicket",
                    method: "POST",
                    data: { ticket: ticket, _token: csrfToken },
                    dataType: "json",
                })
                    .done(function (response) {
                        e.clear().draw();

                        if (response.success) {
                            var tickets = response.tickets;
                            console.log(tickets);

                            tickets.forEach(function (data) {
                                var ticketId = data.id.toString();

                                var ticketExists = false;
                                $(".datatables-permissions tbody tr").each(
                                    function () {
                                        var ticketEnTabla = $(this)
                                            .find("td:eq(1)") // ✅ Columna 1 = ID
                                            .text()
                                            .replace(/\s+/g, "");

                                        if (ticketEnTabla === ticketId) {
                                            ticketExists = true;
                                            return false;
                                        }
                                    }
                                );

                                if (ticketExists) {
                                    return; // no mostrar duplicados
                                }

                                addTicketRow(data);
                            });

                            $("#ticket").val("");
                            $("#ticket").focus();
                        } else {
                            Toast.fire({
                                icon: "error",
                                title: "Número de ticket no encontrado",
                            });
                            playErrorSound();
                        }
                    })
                    .fail(function (error) {
                        console.error("error:", error.responseText);
                        Toast.fire({
                            icon: "error",
                            title: "Error al buscar el ticket",
                        });
                        playErrorSound();
                    })
                    .always(function (response) {
                        $.unblockUI();
                    });
            }
        }
    });

    function addTicketRow(data) {
        var newRow = e.row
            .add({
                "": "",
                id: data.id,
                document: data.document,
                name: data.name,
                product: data.product,
                price: data.price,
                purchase: data.purchase,
                sure: data.sure,
                status: data.status,
            })
            .draw(false);

        var rowNode = newRow.node();

        // ✅ Aplicar estilos según el estado
        if (data.status == 1) {
            // Ticket USADO - Rojo
            $(rowNode).css({
                "background-color": "#db0016ff",
                "border-left": "4px solid #f44336",
            });
            $(rowNode)
                .find(".dt-checkboxes")
                .prop("disabled", true)
                .css("cursor", "not-allowed");
            $(rowNode).addClass("ticket-usado");
        }
    }

    $(".send-data").on("click", function () {
        if (e.rows().count() === 0) {
            Toast.fire({
                icon: "error",
                title: "No hay entradas en la lista",
            });
            return;
        }

        // ✅ Obtener solo los checkboxes marcados manualmente
        var selectedIds = [];
        var rowsToRemove = [];

        $(".datatables-permissions tbody .dt-checkboxes:checked").each(
            function () {
                var row = $(this).closest("tr");
                var rowData = e.row(row).data();

                console.log("Fila seleccionada:", rowData); // ✅ Debug

                if (rowData && rowData.id) {
                    // Verificar que no esté duplicado
                    if (!selectedIds.includes(rowData.id)) {
                        selectedIds.push(rowData.id);
                        rowsToRemove.push(row);
                    }
                }
            }
        );

        if (selectedIds.length === 0) {
            Toast.fire({
                icon: "error",
                title: "No hay entradas seleccionadas",
            });
            return;
        }

        $.blockUI({
            message:
                '<div class="sk-wave mx-auto"><div class="sk-rect sk-wave-rect"></div> <div class="sk-rect sk-wave-rect"></div> <div class="sk-rect sk-wave-rect"></div> <div class="sk-rect sk-wave-rect"></div> <div class="sk-rect sk-wave-rect"></div></div>',
            css: { backgroundColor: "transparent", border: "0" },
            overlayCSS: { opacity: 0.5 },
        });

        var ids = selectedIds.join(",");

        $.ajax({
            url: "printFDTQR",
            method: "POST",
            data: {
                ids: ids,
                _token: csrfToken,
                method: 2,
            },
        })
            .done((response) => {
                if (response.success) {
                    var pdfUrl = response.pdfUrl;

                    var newWindow = window.open(pdfUrl);

                    if (newWindow) {
                        newWindow.onload = function () {
                            newWindow.print();
                        };
                    }

                    e.clear().draw();

                    Toast.fire({
                        icon: "success",
                        title:
                            selectedIds.length +
                            " ticket(s) validado(s) correctamente",
                    });
                    entriesAmount();
                }
            })
            .fail((error) => {
                console.error("Error:", error.responseText);
                Toast.fire({
                    icon: "error",
                    title: "Error al validar los tickets",
                });
            })
            .always(() => {
                $.unblockUI();
            });
    });

    $(".clear-data").on("click", function () {
        e.clear().draw();
    });

    function resetForm() {
        let f = document.getElementById("validate_cupon");

        f.reset();
        $("#validate_message").attr("style", "display: none !important;");
        $("#dni").removeAttr("disabled", true);
        $(".btn_validate").prop("disabled", true);
        localStorage.removeItem("document");
        $("#ticket").focus();
    }
    function entriesAmount() {
        $(".card-numbers-tickets").block({
            message:
                '<div class="sk-wave mx-auto"><div class="sk-rect sk-wave-rect"></div> <div class="sk-rect sk-wave-rect"></div> <div class="sk-rect sk-wave-rect"></div> <div class="sk-rect sk-wave-rect"></div> <div class="sk-rect sk-wave-rect"></div></div>',
            css: { backgroundColor: "transparent", color: "#fff", border: "0" },
            overlayCSS: { opacity: 0.5 },
        });
        $.ajax({
            url: "ticketsValidateFDT",
            method: "get",
            data: { _token: csrfToken },
            dataType: "json",
        }).done(function (response) {
            $("#totalTicky").text(response[0].total);
            $("#validateTicky").text(response[0].active);
            $("#noValidateTicky").text(response[0].inactive);
            $(".card-numbers-tickets").unblock();
        });
    }

    const Toast = Swal.mixin({
        toast: true,
        position: "top",
        showConfirmButton: false,
        timer: 4500,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.onmouseenter = Swal.stopTimer;
            toast.onmouseleave = Swal.resumeTimer;
        },
    });

    function playErrorSound() {
        var audio = new Audio("/audio/error.mp3");
        audio.play();
    }
});

$(document).ready(function () {
    $("#ticket").focus();
});
