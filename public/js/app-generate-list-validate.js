$(() => {
    $("#qrCode").focus();
    entriesAmount();
    var cashierValue = localStorage.getItem("cashier");
    if (cashierValue === null) {
        selectCashier();
        $("#selectBox").modal("show");
    } else {
        $("#cashierName").html(cashierValue);
    }
    var t, a, s;
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
    const sure = {
            0: { title: "DESACTIVADO" },
            1: { title: "ACTIVADO" },
        },
        shift = {
            1: { title: "TURNO COMPLETO" },
            2: { title: "AFTER SCHOOL" },
        },
        device = {
            Seleccione: { title: "SIN DISPOSITIVO" },
            Tarjeta: { title: "TARJETA" },
            Portatarjeta: { title: "TARJETA + LANGER" },
            Pulserasilicona: { title: "PULSERA SILICONA" },
            Pulserafashion: { title: "PULSERA SILICONA AJUSTABLE" },
        };
    s = (
        isDarkStyle
            ? ((t = config.colors_dark.borderColor),
              (a = config.colors_dark.bodyBg),
              config.colors_dark)
            : ((t = config.colors.borderColor),
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
                { data: "document" },
                { data: "name" },
                { data: "shift" },
                { data: "device" },
                { data: "price" },
                { data: "purchase" },
                { data: "sure" },
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
                { targets: 1, searchable: !1 },

                {
                    targets: 2,
                    responsivePriority: 3,
                    render: function (a, e, t, s) {
                        return (
                            "<div class='d-flex flex-column gap-1'><h6 class='mb-0'>" +
                            a +
                            "</h6></div>"
                        );
                    },
                },
                {
                    targets: 3,
                    responsivePriority: 4,
                    render: function (a, e, t, s) {
                        var r = Math.floor(11 * Math.random()) + 1,
                            l;
                        return (
                            "<div class='d-flex flex-column gap-1'><h6 class='mb-0'>" +
                            a +
                            "</h6></div>"
                        );
                    },
                },

                {
                    targets: 4,
                    responsivePriority: 4,
                    render: function (a, e, t, s) {
                        var r = Math.floor(11 * Math.random()) + 1,
                            l;

                        return shift[a].title;
                    },
                },

                {
                    targets: 5,
                    responsivePriority: 4,
                    render: function (a, e, t, s) {
                        var r = Math.floor(11 * Math.random()) + 1,
                            l;
                        return device[a].title;
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
            ],
            language: {
                emptyTable: "No hay registros disponibles en la tabla",
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
            searching: false,
            order: [[2, "asc"]],
            dom: '<"row mx-1"<"col-sm-12 col-md-3" l><"col-sm-12 col-md-9"<"dt-action-buttons text-xl-end text-lg-start text-md-end text-start d-flex align-items-center justify-content-md-end justify-content-center flex-wrap me-1"<"me-3"f>B>>>t<"row mx-2"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
            displayLength: 10,
            lengthMenu: [10, 25, 50, 75, 100],
            buttons: [
                {
                    text: '<i class="mdi mdi-trash-can-outline"></i> LIMPIAR',
                    className:
                        "btn rounded-pill btn-danger waves-effect waves-ligh mt-3",
                },
                {
                    text: '<i class="mdi mdi-check"></i> VALIDAR',
                    className:
                        "send-data btn rounded-pill btn-primary waves-effect waves-ligh mt-3",
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
    $(document).on("click", ".notSelect", function () {
        var cashier = $(this).data("name");
        if ($(this).prop("checked")) {
            $(".notSelect").not(this).prop("disabled", true);
            Swal.fire({
                title: "Estas seguro?",
                text: `Estas eligiendo (${cashier})`,
                icon: "warning",
                showCancelButton: !0,
                confirmButtonText: "Si, elegir CAJA!",
                cancelButtonText: "Cancelar",
                customClass: {
                    confirmButton:
                        "btn btn-primary me-3 waves-effect waves-light",
                    cancelButton: "btn btn-outline-secondary waves-effect",
                },
                buttonsStyling: !1,
            }).then(function (t) {
                if (t.value) {
                    // Verificar si se hizo clic en el botón de confirmación
                    $.ajax({
                        url: "selectBox",
                        type: "post",
                        data: { box: cashier, _token: csrfToken },
                    })
                        .done((response) => {
                            $("#selectBox").modal("hide");
                            localStorage.setItem("cashier", cashier);
                            $("#cashierName").html(cashier);
                            Swal.fire({
                                icon: "success",
                                title: `${cashier} Seleccionado`,
                                customClass: {
                                    confirmButton:
                                        "btn btn-success waves-effect",
                                },
                            });
                        })
                        .fail((response) => {
                            console.log(response.responseText);
                        });
                }
            });
        } else {
            $(".notSelect").not(this).prop("disabled", false);
        }
    });

    $("#qrCode").on("input", function () {
        var ticket = $(this).val();

        if (ticket.length > 9) {
            $.blockUI({
                message:
                    '<div class="sk-wave mx-auto"><div class="sk-rect sk-wave-rect"></div> <div class="sk-rect sk-wave-rect"></div> <div class="sk-rect sk-wave-rect"></div> <div class="sk-rect sk-wave-rect"></div> <div class="sk-rect sk-wave-rect"></div></div>',
                css: { backgroundColor: "transparent", border: "0" },
                overlayCSS: { opacity: 0.5 },
            });
            $.ajax({
                url: "viewList",
                method: "POST",
                data: { ticket: ticket, _token: csrfToken },
                dataType: "json",
            })
                .done(function (response) {
                    $.each(response, function (index, data) {
                        e.row
                            .add({
                                id: data.id,
                                document: data.document,
                                name: data.name,
                                shift: data.shift,
                                device: data.device,
                                price: data.price,
                                purchase: data.income,
                                sure: data.sure,
                            })
                            .draw();
                    });
                })
                .fail(function (error) {
                    console.error(error.responseText);
                    Toast.fire({
                        icon: "error",
                        title: "No existe Ticket",
                    });
                })
                .always(() => {
                    $.unblockUI();
                });
        }
    });

    $(".send-data").on("click", function (v) {
        if (e.rows().count() === 0) {
            Toast.fire({
                icon: "error",
                title: "No hay entradas en la lista",
            });
        } else {
            var ticket = $("#qrCode").val();
            var ids = e.column(1).data().toArray().join(",");

            Swal.fire({
                title: "¿Estás seguro?",
                text: "Esta acción no se podrá revertir.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Sí, validar",
                cancelButtonText: "Cancelar",
                customClass: {
                    confirmButton:
                        "btn btn-primary me-3 waves-effect waves-light",
                    cancelButton: "btn btn-outline-secondary waves-effect",
                },
                buttonsStyling: false,
            }).then(function (result) {
                if (result.isConfirmed) {
                    validarTickets(ticket, ids);
                }
            });
        }
    });

    // Función para validar los tickets
    function validarTickets(ticket, ids) {
        $.blockUI({
            message:
                '<div class="sk-wave mx-auto"><div class="sk-rect sk-wave-rect"></div> <div class="sk-rect sk-wave-rect"></div> <div class="sk-rect sk-wave-rect"></div> <div class="sk-rect sk-wave-rect"></div> <div class="sk-rect sk-wave-rect"></div></div>',
            css: { backgroundColor: "transparent", border: "0" },
            overlayCSS: { opacity: 0.5 },
        });
        $.ajax({
            url: "printTickets",
            method: "POST",
            data: { ids: ids, ticket: ticket, _token: csrfToken },
            dataType: "json",
        })
            .done((response) => {
                // Obtener la URL del PDF desde la respuesta JSON
                var pdfUrl = response.pdfUrl;

                // Abrir una nueva ventana con el PDF generado
                var newWindow = window.open(pdfUrl);

                // Cuando el PDF se cargue en la nueva ventana, imprimir automáticamente
                newWindow.onload = function () {
                    newWindow.print();
                };
                e.clear().draw();
                $("#qrCode").val("").focus();
            })
            .fail((response) => {
                console.log(response.responseText);
            })
            .always(() => {
                $.unblockUI();
            });
    }

    function entriesAmount() {
        $(".card-numbers-tickets").block({
            message:
                '<div class="sk-wave mx-auto"><div class="sk-rect sk-wave-rect"></div> <div class="sk-rect sk-wave-rect"></div> <div class="sk-rect sk-wave-rect"></div> <div class="sk-rect sk-wave-rect"></div> <div class="sk-rect sk-wave-rect"></div></div>',
            css: { backgroundColor: "transparent", color: "#fff", border: "0" },
            overlayCSS: { opacity: 0.5 },
        });
        $.ajax({
            url: "ticketsValidate",
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
        timer: 4000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.onmouseenter = Swal.stopTimer;
            toast.onmouseleave = Swal.resumeTimer;
        },
    });

    function selectCashier() {
        $.ajax({
            url: "boxes",
            type: "GET",
        }).done((response) => {
            const boxes = response.boxes;
            console.log(boxes);

            for (let i = 0; i < 4; i++) {
                const box = boxes[i];
                const isChecked =
                    box.cashier_box !== null && box.cashier_box !== "";
                $("#cashiers1 .row").append(`
                    <div class="col-md-6 mb-md-0 mb-2 gap-2 py-3">
                        <div class="form-check custom-option custom-option-icon ${
                            isChecked ? "checked" : ""
                        } ">
                            <label class="form-check-label custom-option-content" for="customCheckboxIcon${
                                box.id_box
                            }">
                                <span class="custom-option-body">
                                    <i class="mdi mdi-cash-register"></i>
                                    <span class="custom-option-title">${
                                        box.name_box
                                    }</span>
                                </span>
                                <input class="form-check-input ${
                                    isChecked ? "" : "notSelect"
                                }" type="checkbox" value="" id="customCheckboxIcon${
                    box.id_box
                }"  ${isChecked ? "checked disabled" : ""} data-name="${
                    box.name_box
                }">
                            </label>
                        </div>
                    </div>
                `);
            }

            for (let i = 4; i < 8; i++) {
                const box = boxes[i];
                const isChecked =
                    box.cashier_box !== null && box.cashier_box !== "";

                $("#cashiers2 .row").append(`
                <div class="col-md-6 mb-md-0 mb-2 gap-2 py-3">
                    <div class="form-check custom-option custom-option-icon ${
                        isChecked ? "checked" : ""
                    }">
                        <label class="form-check-label custom-option-content" for="customCheckboxIcon${
                            box.id_box
                        }">
                            <span class="custom-option-body">
                                <i class="mdi mdi-cash-register"></i>
                                <span class="custom-option-title">${
                                    box.name_box
                                }</span>
                            </span>
                            <input class="form-check-input  ${
                                isChecked ? "" : "notSelect"
                            }" type="checkbox" value="" id="customCheckboxIcon${
                    box.id_box
                }" ${isChecked ? "checked disabled" : ""} data-name="${
                    box.name_box
                }">
                        </label>
                    </div>
                </div>
                `);
            }
        });
    }
});
