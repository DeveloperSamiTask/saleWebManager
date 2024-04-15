"use strict";
!(function () {})(),
    $(function () {
        const  csrfToken = $('meta[name="csrf-token"]').attr("content");
       
        $("#btn-generate").on("click", function () {+
            $.ajax({
                type: "POST",
                url: "checkInvoice",
                data: { _token: csrfToken,ticket: $("#ticket").text() },
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

                    $("#cardSubmit").attr("style","display:none")
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
