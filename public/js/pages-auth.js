"use strict";
const formAuthentication = document.querySelector("#formAuthentication");
document.addEventListener("DOMContentLoaded", function (e) {
    var t, fv;
    (fv = FormValidation.formValidation(formAuthentication, {
        fields: {
            username: {
                validators: {
                    notEmpty: {
                        message: "Por favor, ingrese su nombre de usuario",
                    },
                },
            },
            password: {
                validators: {
                    notEmpty: {
                        message: "Por favor, introduzca su contraseña",
                    },
                },
            },
        },
        plugins: {
            trigger: new FormValidation.plugins.Trigger(),
            bootstrap5: new FormValidation.plugins.Bootstrap5({
                eleValidClass: "",
                rowSelector: ".mb-3",
            }),
            submitButton: new FormValidation.plugins.SubmitButton(),
            autoFocus: new FormValidation.plugins.AutoFocus(),
        },
    })),
        (t = document.querySelectorAll(".numeral-mask")).length &&
            t.forEach((e) => {
                new Cleave(e, { numeral: !0 });
            });
    fv.on("core.form.valid", function () {
        const submitBtn = document.querySelector(".data-submit");
        const resetBtn = setLoadingState(submitBtn);
        const formData = new FormData(formAuthentication);
        formData.append("_token", $('meta[name="csrf-token"]').attr("content"));
        fetch("initLogin", {
            method: "POST",
            body: formData,
        })
            .then((response) => {
                if (!response.ok) {
                    throw new Error("Error en la solicitud");
                }
                return response.json();
            })
            .then((data) => {
                if (data.icon === "success") {
                    Toast.fire({
                        icon: data.icon,
                        title: data.message,
                    });
                    window.location.href = data.redirect_url;
                } else {
                    Toast.fire({
                        icon: data.icon,
                        title: data.message,
                    });
                }
            })
            .catch((error) => {
                console.error("Error:", error);
            })
            .finally(() => {
                resetBtn();
            });
    });

    function setLoadingState(btnElement) {
        const originalContent = btnElement.innerHTML;
        const originalDisabled = btnElement.disabled;

        btnElement.innerHTML =
            '<span class="spinner-border me-1" role="status" aria-hidden="true"></span>Verificando...';
        btnElement.disabled = true;

        return function resetBtn() {
            btnElement.innerHTML = originalContent;
            btnElement.disabled = originalDisabled;
        };
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
