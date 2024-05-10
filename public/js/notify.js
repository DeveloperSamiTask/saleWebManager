$(function () {
    let csrfToken = $('meta[name="csrf-token"]').attr("content");
    let rol = $(".label-rol").text();

    function viewNotifyAndPush() {
        // Realizar la solicitud AJAX para obtener las notificaciones
        $.ajax({
            url: "Result_Notify",
            type: "GET",
            data: {
                _token: csrfToken,
            },
        })
            .done(function (response) {
                // Verificar si el navegador soporta notificaciones push
                if (
                    "Notification" in window &&
                    Notification.permission === "granted"
                ) {
                    // Iterar sobre las notificaciones recibidas
                    $.each(response, function (index, notification) {
                        if (notification.statusview_notify === 0) {
                            if (
                                rol === "ADMIN" ||
                                (rol === "CONTROLLER" &&
                                notification.type_notify === 1)
                            ) {
                                var newNotification = new Notification(
                                    notification.title_notify,
                                    {
                                        body: notification.body_notify,
                                        icon: "https://lagranjavilla.com/img/logo.png", // URL del icono de la notificación
                                    }
                                );

                                // Manejar clic en la notificación
                                newNotification.onclick = function () {
                                    window.open(
                                        "https://web.lagranjavilla.com/Rectificacion_DNI",
                                        "_blank"
                                    );
                                };
                            } else if (
                                rol === "CAJA" &&
                                title_notify === "Solicitud Aceptada 🪪"
                            ) {
                                var newNotification = new Notification(
                                    notification.type_notify === 2,
                                    {
                                        body: notification.body_notify,
                                        icon: "https://lagranjavilla.com/img/logo.png", // URL del icono de la notificación
                                    }
                                );

                                // Manejar clic en la notificación
                                newNotification.onclick = function () {
                                    window.open(
                                        "https://web.lagranjavilla.com/Boleteria",
                                        "_blank"
                                    );
                                };
                            }

                            $.ajax({
                                url: "Modify_View_Notification",
                                type: "POST",
                                data: {
                                    _token: csrfToken,
                                    id: notification.id_notify,
                                },
                            }).done((e) => {});
                        }
                    });
                }

                // Obtener el contenedor de las notificaciones
                var notificationsContainer = $(".list-group");

                // Limpiar el contenedor antes de agregar nuevas notificaciones
                notificationsContainer.empty();

                // Iterar sobre las notificaciones recibidas
                $.each(response, function (index, notification) {
                    // Crear un elemento de lista para cada notificación
                    var listItem = $("<li>").addClass(
                        "list-group-item list-group-item-action dropdown-notifications-item waves-effect waves-light"
                    );

                    // Construir la estructura interna de la notificación
                    var formattedDate = moment(
                        notification.created_at
                    ).fromNow(); // Formatear la fecha y hora
                    var innerHTML = `
                    <div class="d-flex gap-2">
                        <div class="d-flex flex-column flex-grow-1 overflow-hidden w-px-200">
                            <h6 class="mb-1 text-truncate">${notification.title_notify}</h6>
                            <small class="text-truncate text-body">${notification.body_notify}</small>
                        </div>
                        <div class="flex-shrink-0 dropdown-notifications-actions">
                            <small class="text-muted">${formattedDate}</small>
                        </div>
                    </div>`;

                    // Agregar la estructura HTML al elemento de lista
                    listItem.append(innerHTML);

                    // Agregar el elemento de lista al contenedor de notificaciones
                    notificationsContainer.append(listItem);
                });
            })
            .fail(function (error) {
                console.log(error.responseText);
            });
    }

    setInterval(viewNotifyAndPush, 15000);
});
