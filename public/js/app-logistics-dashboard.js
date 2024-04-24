"use strict";
!(function () {
    let e, t, o, r, chart;
    o = isDarkStyle
        ? ((e = config.colors_dark.textMuted),
          (t = config.colors_dark.headingColor),
          (r = config.colors_dark.bodyColor),
          "dark")
        : ((e = config.colors.textMuted),
          (t = config.colors.headingColor),
          (r = config.colors.bodyColor),
          "light");

    var s = {
        donut: {
            series1: config.colors.success,
            series2: "#43ff64e6",
            series3: "#43ff6473",
            series4: "#43ff6433",
        },
        line: {
            series1: config.colors.warning,
            series2: config.colors.primary,
            series3: "#7367f029",
        },
    };
    var oneWeekAgo = new Date();
    oneWeekAgo.setDate(oneWeekAgo.getDate() - 7);

    // Formatear las fechas
    var startDate = formatDate(oneWeekAgo);
    var endDate = formatDate(new Date());

    fetch("chartEntries")
        .then((response) => response.json())
        .then((response) => {
            let data = response.data;
            let labels = [];
            let entriesPerDay = [];

            for (let date in data) {
                if (data.hasOwnProperty(date)) {
                    labels.push(date);
                    entriesPerDay.push(data[date].total_quantity);
                }
            }

            let maxEntries = Math.max(...entriesPerDay);

            renderChart(entriesPerDay, labels, maxEntries, response.total);
        })
        .catch((error) => {
            console.error("Error al obtener los datos:", error);
        });
    $("#flatpickr-range").flatpickr({
        mode: "range",
        dateFormat: "Y-m-d",
        defaultDate: [startDate, endDate],
        locale: {
            rangeSeparator: " Hasta ",
        },
        onChange: function (selectedDates, dateStr, instance) {
            if (selectedDates && selectedDates.length === 2) {
                $.blockUI({
                    message:
                        '<div class="sk-wave mx-auto"><div class="sk-rect sk-wave-rect"></div> <div class="sk-rect sk-wave-rect"></div> <div class="sk-rect sk-wave-rect"></div> <div class="sk-rect sk-wave-rect"></div> <div class="sk-rect sk-wave-rect"></div></div>',
                    css: { backgroundColor: "transparent", border: "0" },
                    overlayCSS: { opacity: 0.5 },
                });
                var startDate = selectedDates[0].toISOString();
                var endDate = selectedDates[1].toISOString();
                $.ajax({
                    url: "chartEntries",
                    type: "GET",
                    data: {
                        start_date: startDate,
                        end_date: endDate,
                        isChecked: "0",
                    },
                })
                    .done((response) => {
                        if (chart) {
                            chart.destroy();
                        }
                        let entriesPerDay = [];
                        let labels = [];
                        for (let date in response.data) {
                            if (response.data.hasOwnProperty(date)) {
                                labels.push(date);
                                entriesPerDay.push(
                                    response.data[date].total_quantity
                                );
                            }
                        }

                        let maxEntries = Math.max(...entriesPerDay);
                        console.log(entriesPerDay, maxEntries);
                        renderChart(entriesPerDay, labels, maxEntries);
                    })
                    .fail(function (error) {
                        console.error("error:", error.responseText);
                    })
                    .always(function (response) {
                        $.unblockUI();
                    });
            }
        },
    });

    function renderChart(entries, labels, max, total) {
        var a = document.querySelector("#shipmentStatisticsChart");
        $("#total").text(total);
        var i = {
            series: [
                {
                    name: "Entradas",
                    type: "column",
                    data: entries,
                },
            ],
            chart: {
                height: 420,
                type: "bar",
                stacked: !1,
                parentHeightOffset: 0,
                toolbar: { show: 1 },
                zoom: { enabled: 1 },
            },
            grid: { strokeDashArray: 8 },
            colors: [s.line.series1, s.line.series2],
            fill: { opacity: [1, 1] },
            plotOptions: {
                bar: {
                    columnWidth: "30%",
                    startingShape: "rounded",
                    endingShape: "rounded",
                    borderRadius: 4,
                },
            },
            dataLabels: {
                enabled: 1,
                position: "top",
            },
            stroke: { curve: "smooth", lineCap: "round" },
            legend: {
                show: !0,
                position: "bottom",
                markers: { width: 8, height: 8, offsetX: -3 },
                height: 40,
                offsetY: 10,
                itemMargin: { horizontal: 10, vertical: 0 },
                fontSize: "15px",
                fontFamily: "Inter",
                fontWeight: 400,
                labels: { colors: t, useSeriesColors: !1 },
                offsetY: 10,
            },
            xaxis: {
                tickAmount: entries.length,
                categories: labels,
                labels: {
                    style: {
                        colors: e,
                        fontSize: "13px",
                        fontFamily: "Inter",
                        fontWeight: 400,
                    },
                },
                axisBorder: { show: !1 },
                axisTicks: { show: !1 },
            },
            yaxis: {
                tickAmount: 8,
                min: 5,
                max: max + 5,
                labels: {
                    style: {
                        colors: e,
                        fontSize: "13px",
                        fontFamily: "Inter",
                        fontWeight: 400,
                    },
                    formatter: function (e) {
                        return Math.round(e);
                    },
                },
                forceNiceScale: 1,
            },
            fill: {
                opacity: 1,
            },
            tooltip: {
                y: {
                    formatter: function (val) {
                        return val;
                    },
                },
            },
            responsive: [
                {
                    breakpoint: 1400,
                    options: {
                        chart: { height: 270 },
                        xaxis: { labels: { style: { fontSize: "10px" } } },
                        legend: {
                            itemMargin: { vertical: 0, horizontal: 10 },
                            fontSize: "13px",
                            offsetY: 12,
                        },
                    },
                },
                {
                    breakpoint: 1399,
                    options: {
                        chart: { height: 415 },
                        plotOptions: { bar: { columnWidth: "50%" } },
                    },
                },
                {
                    breakpoint: 982,
                    options: { plotOptions: { bar: { columnWidth: "30%" } } },
                },
                {
                    breakpoint: 480,
                    options: { chart: { height: 250 }, legend: { offsetY: 7 } },
                },
            ],
        };

        if (a !== null) {
            chart = new ApexCharts(a, i);
            chart.render();
        }
    }

    function formatDate(date) {
        var year = date.getFullYear();
        var month = (date.getMonth() + 1).toString().padStart(2, "0");
        var day = date.getDate().toString().padStart(2, "0");
        var hours = date.getHours().toString().padStart(2, "0");
        var minutes = date.getMinutes().toString().padStart(2, "0");
        var seconds = date.getSeconds().toString().padStart(2, "0");
        return `${year}-${month}-${day}T${hours}:${minutes}:${seconds}`;
    }
})(),
    $(function () {
        const e = $(".dt-route-vehicles"),
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
        e.length &&
            (e.DataTable({
                ajax: "Tabla_Dashboard",
                columns: [
                    { data: "id" },
                    { data: "nameP" },
                    { data: "shift" },
                    { data: "device" },
                    { data: "price" },
                    { data: "used" },
                    { data: "cashier" },
                    { data: "box" },
                ],
                columnDefs: [
                    {
                        targets: 2,
                        render: function (a, e, t, s) {
                            return shift[a]?.title ?? "Turno Completo";
                        },
                    },
                    {
                        targets: 3,
                        render: function (a, e, t, s) {
                            return device[a]?.title ?? "No seleccionado";
                        },
                    },
                ],
                language: {
                    url: "json/datatable-spanish.json",
                },
                order: [2, "asc"],
                dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"f>><"table-responsive"t><"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                displayLength: 10,
                lengthMenu: [10, 25, 50, 75, 100],
                responsive: {
                    details: {
                        display: $.fn.dataTable.Responsive.display.modal({
                            header: function (e) {
                                return "Details of " + e.data().location;
                            },
                        }),
                        type: "column",
                        renderer: function (e, t, o) {
                            o = $.map(o, function (e, t) {
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
                                !!o &&
                                $('<table class="table"/><tbody />').append(o)
                            );
                        },
                    },
                },
            }),
            $(".dataTables_info").addClass("pt-0"));
    });
