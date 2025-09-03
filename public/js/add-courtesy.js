"use strict";
!(function () {
    const d_issue = document.querySelector("#date_issue");

    flatpickr.localize(flatpickr.l10ns.es);

    d_issue && d_issue.flatpickr({ monthSelectorType: "static" });
})(),
    $(function () {
        var e,
            t = $(".sticky-element"),
            csrfToken = $('meta[name="csrf-token"]').attr("content"),
            t =
                (window.Helpers.initCustomOptionCheck(),
                (e = Helpers.isNavbarFixed()
                    ? $(".layout-navbar").height() - 3
                    : 0),
                t.length && t.sticky({ topSpacing: e, zIndex: 9 }),
                $(".select2"));
        t.length &&
            t.each(function () {
                var e = $(this);
                select2Focus(e),
                    e.wrap('<div class="position-relative"></div>').select2({
                        placeholder: e.data("placeholder") || "Seleccione",
                        dropdownParent: e.parent(),
                    });
            });

        let comboIndex = 0;

        function renderComboRow(index) {
            const options = combos
                .map(
                    (c) =>
                        `<option value="${c.id}" data-members="${c.members}">${c.name}</option>`
                )
                .join("");
            return `
        <tr data-index="${index}">
            <td>
                <select name="combos[${index}][id]" class="form-select combo-select" required>
                    <option value="">Seleccione un combo</option>
                    ${options}
                </select>
            </td>
            <td>
                <input type="number" name="combos[${index}][quantity]" class="form-control combo-qty" min="1" value="1" required>
            </td>
            <td>
                <button type="button" class="btn btn-danger waves-effect waves-light remove-combo">Eliminar</button>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <div class="members-container" id="members-${index}"></div>
            </td>
        </tr>
    `;
        }

        function renderMembersFields(index, comboId, quantity) {
            const combo = combos.find((c) => c.id == comboId);
            const membersPerCombo = combo ? combo.members : 0;
            const totalMembers = membersPerCombo * quantity;

            const container = $(`#members-${index}`);
            container.empty();

            for (let i = 0; i < totalMembers; i++) {
                container.append(`
            <div class="row mb-2">
                <div class="col-md-6">
                    <input type="text" name="combos[${index}][members][${i}][name]" class="form-control" placeholder="Nombre del miembro ${
                    i + 1
                }" required>
                </div>
                <div class="col-md-6">
                    <input type="text" name="combos[${index}][members][${i}][dni]" class="form-control" placeholder="DNI del miembro ${
                    i + 1
                }" required>
                </div>
            </div>
        `);
            }
        }

        function getCombosData() {
            const result = [];

            $("#comboTable tbody tr[data-index]").each(function () {
                const index = $(this).data("index");
                const comboId = $(this).find(".combo-select").val();
                const quantity =
                    parseInt($(this).find(".combo-qty").val()) || 0;

                const members = [];

                $(`#members-${index} input`).each(function (i) {
                    const inputName = $(this).attr("name");
                    const value = $(this).val();
                    const memberIndex = Math.floor(i / 2);

                    if (!members[memberIndex]) {
                        members[memberIndex] = {};
                    }

                    if (inputName.includes("[name]")) {
                        members[memberIndex].name = value;
                    } else if (inputName.includes("[dni]")) {
                        members[memberIndex].dni = value;
                    }
                });

                result.push({
                    combo_id: comboId,
                    quantity: quantity,
                    miembros: members, // esto es lo que espera el backend según tu estructura
                });
            });

            return result;
        }

        $("#addCombo").on("click", function () {
            const html = renderComboRow(comboIndex);
            $("#comboTable tbody").append(html);
            comboIndex++;
            updateEmptyMessage();
            getCombosData();
        });

        // Cambios en combo o cantidad
        $(document).on("change", ".combo-select, .combo-qty", function () {
            const row = $(this).closest("tr[data-index]");
            const index = row.data("index");
            const comboId = row.find(".combo-select").val();
            const quantity = parseInt(row.find(".combo-qty").val()) || 0;

            renderMembersFields(index, comboId, quantity);
            getCombosData();
        });

        // Cambios en campos de miembros
        $(document).on("input", 'input[name^="combos"]', function () {
            getCombosData();
        });

        // Eliminar combo
        $(document).on("click", ".remove-combo", function () {
            const row = $(this).closest("tr");
            const nextRow = row.next();
            row.remove();
            nextRow.remove();
            updateEmptyMessage();
            getCombosData();
        });

        $(document).on("keydown", ".combo-qty", function (e) {
            if (e.key === "Enter") {
                e.preventDefault(); // Evita que el formulario se envíe
                $(this).blur(); // Opcional: quita el foco del input
            }
        });

        $("#paymentLinkForm").on("keydown", "input", function (e) {
            if (e.key === "Enter") {
                e.preventDefault();
            }
        });

        function updateEmptyMessage() {
            const hasCombos = $("#comboTableBody tr[data-index]").length > 0;
            if (hasCombos) {
                $(".combo-empty-message").remove();
            } else {
                $("#comboTableBody").html(`
            <tr class="combo-empty-message">
                <td colspan="3" class="text-center text-muted">📦 Elige tus combos para comenzar</td>
            </tr>
        `);
            }
        }

        const f = document.getElementById("paymentLinkForm");

        const fv = FormValidation.formValidation(f, {
            fields: {
                code: {
                    validators: {
                        notEmpty: { message: "Ingresa el codigo de compra" },
                    },
                },
                lastname: {
                    validators: {
                        notEmpty: {
                            message: "Ingresa el apellido del cliente",
                        },
                    },
                },
                names: {
                    validators: {
                        notEmpty: {
                            message: "Ingresa el nombre del cliente",
                        },
                    },
                },
                document: {
                    validators: {
                        notEmpty: {
                            message: "Selecciona el tipo de documento",
                        },
                    },
                },
                number_doc: {
                    validators: {
                        notEmpty: {
                            message: "Ingresa el número de documento",
                        },
                        numeric: {
                            message: "El número de documento debe ser numérico",
                        },
                    },
                },
                phone: {
                    validators: {
                        notEmpty: {
                            message: "Ingresa el número de teléfono",
                        },
                        numeric: {
                            message: "El número de teléfono debe ser numérico",
                        },
                    },
                },
                date_issue: {
                    validators: {
                        notEmpty: {
                            message: "Selecciona la fecha de ingreso",
                        },
                        date: {
                            format: "YYYY-MM-DD",
                            message: "La fecha de ingreso no es válida",
                        },
                    },
                },
            },
            plugins: {
                trigger: new FormValidation.plugins.Trigger(),
                bootstrap5: new FormValidation.plugins.Bootstrap5({
                    eleValidClass: "is-valid",
                    rowSelector: function (t, e) {
                        switch (e) {
                            case "formValidationName":
                            case "formValidationEmail":
                            case "formValidationPass":
                            case "formValidationConfirmPass":
                            case "formValidationFile":
                            case "formValidationDob":
                            case "formValidationSelect2":
                            case "formValidationLang":
                            case "formValidationTech":
                            case "formValidationHobbies":
                            case "formValidationBio":
                            case "formValidationGender":
                                return ".col-md-6";
                            case "formValidationPlan":
                                return ".col-xl-3";
                            case "formValidationSwitch":
                            case "formValidationCheckbox":
                                return ".col-12";
                            default:
                                return ".row";
                        }
                    },
                }),
                submitButton: new FormValidation.plugins.SubmitButton(),
                autoFocus: new FormValidation.plugins.AutoFocus(),
            },
        });

        fv.on("core.form.valid", function () {
            const combos = getCombosData().filter((c) => c.quantity > 0);

            if (combos.length === 0) {
                Toast.fire({
                    icon: "warning",
                    title: "Agrega al menos un combo con cantidad mayor a 0.",
                });
                return;
            }

            blockUI();

            const formData = new FormData(f);
            formData.append("_token", csrfToken);

            formData.append("combos", JSON.stringify(combos));

            fetch("store", {
                method: "POST",
                body: formData,
                headers: {
                    "X-CSRF-TOKEN": document.querySelector(
                        'meta[name="csrf-token"]'
                    ).content,
                },
            })
                .then((response) => response.json())
                .then((data) => {
                    if (data.success) {
                        Toast.fire({
                            icon: data.icon || "success",
                            title: data.message,
                        });

                        f.reset();

                        // ✅ Reiniciar Select2 manualmente (si usas Select2)
                        $("#document").val(null).trigger("change");

                        // ✅ Limpiar la tabla de combos
                        const comboTableBody =
                            document.getElementById("comboTableBody");
                        comboTableBody.innerHTML = `
                        <tr class="combo-empty-message">
                            <td colspan="3" class="text-center text-muted">📦 Elige tus combos para comenzar</td>
                        </tr>`;

                        $.unblockUI();

                        // ✅ Esperar 3 segundos y luego recargar
                        setTimeout(() => {
                            location.reload();
                        }, 3000);
                    } else {
                        $.unblockUI();
                    }
                })
                .catch((error) => {
                    $.unblockUI();
                    Toast.fire({
                        icon: "error",
                        title:
                            error.message || "Error al procesar la solicitud.",
                    });

                    console.error("Error:", error);
                });
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
            timer: 4500,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.onmouseenter = Swal.stopTimer;
                toast.onmouseleave = Swal.resumeTimer;
            },
        });
    });
