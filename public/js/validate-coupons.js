!(function () {
    var e = document.querySelectorAll(".phone-mask"),
        az = document.querySelector("#autosize-demo"),
        r = document.querySelectorAll(".dob-picker"),
        o = document.querySelectorAll(".form-check-input-payment");
    e &&
        e.forEach(function (e) {
            new Cleave(e, { phone: !0, phoneRegionCode: "US" });
        }),
        az && autosize(az),
        r &&
            r.forEach(function (e) {
                e.flatpickr({ monthSelectorType: "static" });
            }),
        o &&
            o.forEach(function (e) {
                e.addEventListener("change", function (e) {
                    "credit-card" === e.target.value
                        ? document
                              .querySelector("#form-credit-card")
                              .classList.remove("d-none")
                        : document
                              .querySelector("#form-credit-card")
                              .classList.add("d-none");
                });
            });
})(),
    $(function () {
        var e,
            t = $(".select2");
        t.length &&
            t.each(function () {
                var e = $(this);
                select2Focus(e),
                    e.wrap('<div class="position-relative"></div>').select2({
                        placeholder: "Select value",
                        dropdownParent: e.parent(),
                    });
            });

        $("#code").on("input", function () {
            var e = $("#code").val().trim();

            if (e.length == 15) {
                searchCode(e);
            }
        });

        $("#validateCode").click(function () {
            var e = $("#code").val();
            validateR(e);
        });

        function searchCode(i) {
            blockUI();

            fetch("Search/" + i, {
                method: "GET",
                headers: {
                    "Content-Type": "application/json",
                },
            })
                .then((response) => {
                    if (!response.ok) {
                        return response.json().then((err) => {
                            throw err;
                        });
                    }
                    return response.json();
                })
                .then((data) => {
                    const row = data.data;
                    document.getElementById("coupon-alert").innerHTML = "";
                    displayCouponInfo(row);

                    $("#expiration").val(row.expired_date);
                    $("#promotion").val(row.promotion.name);
                    $("#typeDoc").val(
                        row.type_doc === 1 ? "DNI" : "CARNET EXTRANJERIA"
                    );
                    $("#numberDoc").val(row.number_doc);
                    $("#names").val(
                        `${row.father_surname} ${row.mother_surname} ${row.names}`
                    );
                })
                .catch((error) => {
                    console.error("Error:", error);

                    const form = document.getElementById("formCoupon"); // Asegúrate de que el formulario tenga el id 'formID'
                    form.reset();

                    $("#code").val(i);

                    showAlert(
                        error.message || "Hubo un problema con la operación",
                        "danger"
                    );
                })
                .finally(() => {
                    $.unblockUI();
                });
        }

        function validateR(i) {
            blockUI();
            fetch("validateR/" + i, {
                method: "GET",
                headers: {
                    "Content-Type": "application/json",
                },
            })
                .then((response) => {
                    if (!response.ok) {
                        throw new Error("Network response was not ok");
                    }
                    return response.json();
                })
                .then((data) => {
                    Toast.fire({
                        icon: data.icon,
                        title: data.message,
                    });

                    var pdfUrl = data.pdfUrl;

                    console.log(pdfUrl);

                    var newWindow = window.open(pdfUrl);

                    newWindow.onload = function () {
                        newWindow.print();
                    };
                })
                .catch((error) => {
                    console.error(
                        "There was a problem with the fetch operation:",
                        error
                    );
                })
                .finally(() => {
                    $.unblockUI();
                });
        }

        function showAlert(message, type) {
            let alertBox = document.getElementById("coupon-alert");

            alertBox.innerHTML = `
                <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                    <i class="mdi mdi-alert-circle-outline me-2"></i> ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            `;
            setTimeout(() => {
                alertBox.innerHTML = "";
            }, 10000);
        }

        // Función para mostrar los datos del cupón (puedes personalizarla)
        function displayCouponInfo(coupon) {
            console.log("Cupón válido:", coupon);
            // Aquí puedes actualizar el DOM con la información del cupón
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
            position: "top-end",
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.onmouseenter = Swal.stopTimer;
                toast.onmouseleave = Swal.resumeTimer;
            },
        });
    });
