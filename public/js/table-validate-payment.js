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
                                    statusText = a.status.toUpperCase(); // fallback
                            }

                            return `
            <button
                class="btn btn-sm ${
                    a.status === "unused" ? "btn-danger" : "btn-success"
                } btnValidate"
                data-id="${a.id}"
                data-names="${a.names}"
                data-status="${a.status}"
                type="button"
                data-bs-toggle="modal"
                data-bs-target="#validateModal">
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
    $("#qrCode").change(function () {
        const code = this.value;

        blockUI();

        if (code) {
            $.ajax({
                url: `qr/details/${code}`,
                type: "GET",
            })
                .done((response) => {
                    const table = $(".dt-row-grouping").DataTable();
                    table.clear().rows.add(response.data).draw();

                    $("#names").text(response.link.names);
                    $("#documento").text(response.link.document);

                    let translatedStatus = "";
                    switch (response.link.status) {
                        case "unused":
                            translatedStatus = "SIN USAR";
                            break;
                        case "used":
                            translatedStatus = "USADO";
                            break;
                        default:
                            translatedStatus =
                                response.link.status.toUpperCase();
                    }

                    $("#status").html(
                        `<span class="badge bg-${
                            response.link.status === "unused"
                                ? "danger"
                                : "success"
                        } fs-6">${translatedStatus}</span>`
                    );
                })
                .fail((err) => {
                    console.error("Error:", err);
                    alert("Error al obtener los detalles del QR.");
                })
                .always(() => {
                    $.unblockUI();
                });
        }
    });

    t.on("click", ".btnValidate", function () {
    const id = $(this).data("id");
    const name = $(this).data("names");

    $("#modalId").text(id);
    $("#modalName").text(name);
    $("#hiddenRecordId").val(id); // para usarlo en validación posterior
});

    function blockUI() {
        $.blockUI({
            message:
                '<div class="sk-wave mx-auto"><div class="sk-rect sk-wave-rect"></div> <div class="sk-rect sk-wave-rect"></div> <div class="sk-rect sk-wave-rect"></div> <div class="sk-rect sk-wave-rect"></div> <div class="sk-rect sk-wave-rect"></div></div>',
            css: { backgroundColor: "transparent", border: "0" },
            overlayCSS: { opacity: 0.5 },
        });
    }
});
