$(() => {
    var t, a, s;
    entriesAmount();
    var status = {
        ACTIVO: {
            title: "ACTIVO",
            class: "badge rounded-pill bg-label-success",
        },
        DESACTIVADO: {
            title: "DESACTIVADO",
            class: "badge rounded-pill bg-label-danger",
        },
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
                { data: "document" },
                { data: "name" },
                { data: "product" },
                { data: "price" },
                { data: "purchase" },
                { data: "sure" },
                { data: "" },
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
                            "<h6 class='text-truncate d-flex align-items-center mb-0'>" +
                            a +
                            "</h6>"
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
                            "</span></div></div><div class='d-flex flex-column gap-1'><a href='pages-profile-user.html' class='text-truncate'><h6 class='mb-0'>" +
                            a +
                            "</h6></a></div></div>"
                        );
                    },
                },
                {
                    targets: 7,
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
                    searchable: !1,
                    class: "text-center",
                    orderable: !1,
                    render: function (e, t, a, n) {
                        return '<span class="text-nowrap"><button class="btn btn-sm btn-icon btn-text-secondary rounded-pill btn-icon delete-record"><i class="mdi mdi-delete-outline mdi-20px"></i></button></span>';
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
            displayLength: 7,
            lengthMenu: [7, 10, 25, 50, 75, 100],
            buttons: [
                {
                    text: "Agregar Entrada",
                    className:
                        "add-new btn rounded-pill btn-info waves-effect waves-light mt-3",
                    attr: {
                        "data-bs-toggle": "modal",
                        "data-bs-target": "#addPermissionModal",
                    },
                    init: function (e, t, a) {
                        $(t).removeClass("btn-secondary");
                    },
                },
                {
                    text: "ENVIAR",
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

    $("#addPermissionModal").on("shown.bs.modal", function () {
        $("#ticket").focus();
    });

    $("#ticket").on("input", function (e) {
        var ticket = $(this).val();
        var ticketExists = false;
        if (ticket.length > 5) {
            $(".datatables-permissions tbody tr").each(function () {
                var ticketEnTabla = $(this).find("td:eq(1)").text(); // Suponiendo que el ticket está en la segunda columna
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
            } else {
                $.blockUI({
                    message:
                        '<div class="sk-wave mx-auto"><div class="sk-rect sk-wave-rect"></div> <div class="sk-rect sk-wave-rect"></div> <div class="sk-rect sk-wave-rect"></div> <div class="sk-rect sk-wave-rect"></div> <div class="sk-rect sk-wave-rect"></div></div>',
                    css: { backgroundColor: "transparent", border: "0" },
                    overlayCSS: { opacity: 0.5 },
                });
                $.ajax({
                    url: "viewTicket",
                    method: "POST",
                    data: { ticket: ticket, _token: csrfToken },
                    dataType: "json",
                })
                    .done(function (response) {
                        if (response.success) {
                            var data = response.ticket;

                            if (data.status === 1) {
                                $("#dni").attr("disabled", true);
                                $("#validate_message").show();
                                $("#date_use").text(data.used);
                            } else if (
                                data.income != getCurrentDate() &&
                                data.sure == 0
                            ) {
                                $("#dni").attr("disabled", true);
                                $("#income_message").show();
                            } else {
                                $("#dni").removeAttr("disabled", true);
                                $("#validate_message").attr(
                                    "style",
                                    "display: none !important;"
                                );
                                $("#income_message").attr(
                                    "style",
                                    "display: none !important;"
                                );
                                $("#date_use").text("");
                                $("#dni").focus();
                            }

                            $("#names").val(data.name);
                            $("#shift").val(shift[data.shift].title);
                            $("#device").val(device[data.device].title);
                            $("#price").val(data.price);

                            var purchaseDate = moment(data.purchase),
                                formattedDate = purchaseDate.format(
                                    "MMMM DD, YYYY hh:mm A"
                                );
                            $("#pruchase_date").val(
                                formattedDate.charAt(0).toUpperCase() +
                                    formattedDate.slice(1)
                            );
                            $("#admission_date").val(data.income);
                            $("#sure").val(sure[data.sure].title);
                            localStorage.setItem("document", data.document);
                        } else {
                            Toast.fire({
                                icon: "error",
                                title: "Número de ticket no encontrado",
                            });
                        }
                    })
                    .fail(function (error) {
                        console.error("error:", error.responseText);
                    })
                    .always(function (response) {
                        $.unblockUI();
                    });
            }
        }
    });

    $("#dni").on("input", function (e) {
        const dniValue = $(this).val();
        const dniLength = dniValue.length;
        const isValidLength =
            dniLength === 8 ||
            dniLength === 9 ||
            dniLength === 10 ||
            dniLength === 12;

        if (isValidLength) {
            const isValidDNI = dniValue === localStorage.getItem("document");

            Toast.fire({
                icon: isValidDNI ? "success" : "error",
                title: isValidDNI
                    ? "Número de DNI válido"
                    : "Número de DNI inválido",
            });

            if (isValidDNI) {
                $(".btnChangeDNI").prop("style", "display:none");
            } else {
                $(".btnChangeDNI").prop("style", "display:block");
            }

            $(".btn_validate").prop("disabled", !isValidDNI);
        } else {
            $(".btnChangeDNI").prop("style", "display:none");
            Toast.close();
            $(".btn_validate").prop("disabled", true);
        }
    });

    $("#btnDNI").on("click", function () {
        $("#addPermissionModal").modal("hide");

        $("#appName").text($("#names").val());
        $("#appCodeEntrie").text("#" + $("#ticket").val());
        $("#appID").val($("#ticket").val());
        $("#appINames").val($("#names").val());

        $("#modalDNI").modal("show");
    });

    const overview = document.querySelector("#overviewChart");
    let chartInstance = null;

    $("#appDNI").on("input", function (e) {
        const document = $(this).val();
        const sessiondoc = localStorage.getItem("document");
        const dniLength = document.length;
        const isValidLength = dniLength === 8;

        if (isValidLength) {
            let percentage = percentMatch(document, sessiondoc);
            $("#appDNIBefore").val(sessiondoc);
            if (chartInstance) {
                // Destruir el gráfico existente antes de crear uno nuevo
                chartInstance.destroy();
            }

            if (percentage >= 75) {
                Toast.fire({
                    icon: "success",
                    title: "Puedes enviar la solicitud",
                });
                $("#appBtnSend").prop("disabled", false);
            } else {
                Toast.fire({
                    icon: "warning",
                    title: "Llama a un supervisor",
                });
                $("#appBtnSend").prop("disabled", true);
            }

            v = {
                chart: {
                    height: 134,
                    type: "radialBar",
                    sparkline: { enabled: !0 },
                },
                plotOptions: {
                    radialBar: {
                        hollow: { size: "55%" },
                        dataLabels: {
                            name: { show: !1 },
                            value: {
                                show: !0,
                                offsetY: 5,
                                fontWeight: 500,
                                fontSize: "1rem",
                                fontFamily: "Inter",
                                color: config.colors.primary,
                            },
                        },
                        track: {
                            background: config.colors_label.secondary,
                        },
                    },
                },
                states: {
                    hover: { filter: { type: "none" } },
                    active: { filter: { type: "none" } },
                },
                stroke: { lineCap: "round" },
                colors: [config.colors.primary],
                grid: { padding: { bottom: -15 } },
                series: [percentage],
                labels: ["Progress"],
            };

            chartInstance = new ApexCharts(overview, v);
            chartInstance.render();
        } else {
            $("#appBtnSend").prop("disabled", true);
        }
    });

    $(".btn_validate").on("click", function () {
        e.row
            .add({
                id: $("#ticket").val(),
                document: $("#dni").val(),
                name: $("#names").val(),
                product: $("#shift").val() + " - " + $("#device").val(),
                price: "S/. " + $("#price").val(),
                purchase: $("#pruchase_date").val(),
                sure: $("#sure").val(),
            })
            .draw();
        Toast.fire({
            icon: "success",
            title: "Entrada Agregado",
        });
        resetForm();
    });

    $(".send-data").on("click", function () {
        if (e.rows().count() === 0) {
            Toast.fire({
                icon: "error",
                title: "No hay entradas en la lista",
            });
        } else {
            var ids = e.column(1).data().toArray().join(",");
            $("#twoFactorAuth").modal("show");
        }
    });
    $(".send-whatsapp").on("click", function () {
        $.blockUI({
            message:
                '<div class="sk-wave mx-auto"><div class="sk-rect sk-wave-rect"></div> <div class="sk-rect sk-wave-rect"></div> <div class="sk-rect sk-wave-rect"></div> <div class="sk-rect sk-wave-rect"></div> <div class="sk-rect sk-wave-rect"></div></div>',
            css: { backgroundColor: "transparent", border: "0" },
            overlayCSS: { opacity: 0.5 },
        });
        var ids = e.column(1).data().toArray().join(",");
        var phone = $("#phoneText").val();
        $.ajax({
            url: "sendWhatsapp",
            method: "POST",
            data: { ids: ids, _token: csrfToken, phone: phone, method: 1 },
        })
            .done((response) => {
                var url =
                    "https://web.lagranjavilla.com/token/" + response.token;
                var message =
                    "¡Hola! Para acceder al QR y visitar La Granja Villa, por favor haz clic en el siguiente enlace:\n\n" +
                    url;
                var whatsappUrl =
                    "https://api.whatsapp.com/send?phone=51" +
                    phone +
                    "&text=" +
                    encodeURIComponent(message);

                // Abrir la página de WhatsApp en una nueva pestaña
                window.open(whatsappUrl, "_blank");
            })
            .fail((error) => {
                console.log(error.responseText);
            })
            .always(() => {
                $.unblockUI();
            });
    });

    $("#print_qr").on("click", function () {
        $.blockUI({
            message:
                '<div class="sk-wave mx-auto"><div class="sk-rect sk-wave-rect"></div> <div class="sk-rect sk-wave-rect"></div> <div class="sk-rect sk-wave-rect"></div> <div class="sk-rect sk-wave-rect"></div> <div class="sk-rect sk-wave-rect"></div></div>',
            css: { backgroundColor: "transparent", border: "0" },
            overlayCSS: { opacity: 0.5 },
        });
        var ids = e.column(1).data().toArray().join(",");
        $.ajax({
            url: "printQR",
            method: "POST",
            data: { ids: ids, _token: csrfToken, method: 2 },
        })
            .done((response) => {
                var pdfUrl = response.pdfUrl;

                var newWindow = window.open(pdfUrl);

                newWindow.onload = function () {
                    newWindow.print();
                };
            })
            .fail((error) => {
                console.log(error.responseText);
            })
            .always(() => {
                $.unblockUI();
            });
    });

    $(".btn-finish").on("click", function () {
        e.clear().draw();
        $("#twoFactorAuth").modal("hide");
        $("#twoFactorAuthOne").modal("hide");
    });
    $("#formChangeDNI").on("submit", function (event) {
        event.preventDefault();
        $.blockUI({
            message:
                '<div class="sk-wave mx-auto"><div class="sk-rect sk-wave-rect"></div> <div class="sk-rect sk-wave-rect"></div> <div class="sk-rect sk-wave-rect"></div> <div class="sk-rect sk-wave-rect"></div> <div class="sk-rect sk-wave-rect"></div></div>',
            css: { backgroundColor: "transparent", border: "0" },
            overlayCSS: { opacity: 0.5 },
        });
        let requestData = $(this).serialize() + "&_token=" + csrfToken;

        $.ajax({
            url: "requestChangeDni",
            method: "POST",
            data: requestData,
        })
            .done((response) => {
                Toast.fire({
                    icon: response.icon,
                    title: response.message,
                });
                $("#appBtnSend").prop("disabled", true);
                resetApp();
            })
            .fail((error) => {
                console.log(error.responseText);
            })
            .always(() => {
                $.unblockUI();
            });
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

    function percentMatch(str1, str2) {
        const longitud = Math.max(str1.length, str2.length);
        let coincidencias = 0;
        for (let i = 0; i < longitud; i++) {
            if (str1[i] && str2[i] && str1[i] === str2[i]) {
                coincidencias++;
            }
        }
        return (coincidencias / longitud) * 100;
    }
    function resetApp() {
        $("#formChangeDNI").trigger("reset");
        $("#modalDNI").modal("hide");
        $("#addPermissionModal").modal("show");
        $("#appBtnSend").prop("disabled", true);
        $(".btnChangeDNI").prop("style", "display:none");

        resetForm();
    }
    function getCurrentDate() {
        var today = new Date();
        var year = today.getFullYear();
        var month = (today.getMonth() + 1).toString().padStart(2, "0");
        var day = today.getDate().toString().padStart(2, "0");
        return year + "-" + month + "-" + day;
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
});
