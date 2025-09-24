"use strict";
!(function () {})(),
    $(function () {
        const csrfToken = $('meta[name="csrf-token"]').attr("content");
        $("#typeDoc").select2();

        const prefixSpan = document.getElementById("prefix_doc");

        $("#typeDoc").on("change", function () {
            const value = $(this).val();
            if (value === "BOLETA") {
                prefixSpan.innerHTML =
                    "<i class='mdi mdi-alpha-b-box fs-3'></i>"; // Boleta
            } else if (value === "FACTURA") {
                prefixSpan.innerHTML =
                    "<i class='mdi mdi-alpha-f-box fs-3'></i>"; // Factura
            } else {
                prefixSpan.innerHTML = "";
            }
        });

        $("#btn-generate").on("click", function () {
            const typeDoc = $("#typeDoc").val();
            const number = $("#number-input").val();

            // Validación
            if (!typeDoc) {
                Toast.fire({
                    icon: "warning",
                    title: "Selecciona un tipo de documento",
                });
                return; // Sale del click, no hace el ajax
            }

            if (!number || number.trim() === "") {
                Toast.fire({
                    icon: "warning",
                    title: "Ingresa un número válido",
                });
                return; // Sale del click, no hace el ajax
            }

            $.ajax({
                type: "POST",
                url: "checkInvoice",
                data: {
                    _token: csrfToken,
                    ticket: $("#ticket").text(),
                    typeDoc: typeDoc,
                    number: number,
                },
                beforeSend: () => {
                    $.blockUI({
                        message:
                            '<div class="sk-wave mx-auto"><div class="sk-rect sk-wave-rect"></div> <div class="sk-rect sk-wave-rect"></div> <div class="sk-rect sk-wave-rect"></div> <div class="sk-rect sk-wave-rect"></div> <div class="sk-rect sk-wave-rect"></div></div>',
                        css: { backgroundColor: "transparent", border: "0" },
                        overlayCSS: { opacity: 0.5 },
                    });
                },
            })
                .done((response) => {
                    Toast.fire({
                        icon: "success",
                        title: response.message,
                    });

                    $("#cardSubmit").attr("style", "display:none");
                })
                .fail((err) => {
                    console.error(err.responseText);
                })
                .always(() => {
                    $.unblockUI();
                });
        });

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
