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
        .then(({ data }) => {
            let labels = [];
            let entriesPerDay = [];

            for (let date in data) {
                if (data.hasOwnProperty(date)) {
                    labels.push(date);
                    entriesPerDay.push(data[date].total_quantity);
                }
            }

            let maxEntries = Math.max(...entriesPerDay);

            renderChart(entriesPerDay, labels, maxEntries);
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

    function renderChart(entries, labels, max) {
        var a = document.querySelector("#shipmentStatisticsChart");
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
        var e = $(".dt-route-vehicles");
        e.length &&
            (e.DataTable({
                ajax: assetsPath + "json/logistics-dashboard.json",
                columns: [
                    { data: "id" },
                    { data: "id" },
                    { data: "location" },
                    { data: "start_city" },
                    { data: "end_city" },
                    { data: "warnings" },
                    { data: "progress" },
                ],
                columnDefs: [
                    {
                        className: "control",
                        orderable: !1,
                        searchable: !1,
                        responsivePriority: 2,
                        targets: 0,
                        render: function (e, t, o, r) {
                            return "";
                        },
                    },
                    {
                        targets: 1,
                        orderable: !1,
                        searchable: !1,
                        checkboxes: !0,
                        checkboxes: {
                            selectAllRender:
                                '<input type="checkbox" class="form-check-input">',
                        },
                        responsivePriority: 3,
                        render: function () {
                            return '<input type="checkbox" class="dt-checkboxes form-check-input">';
                        },
                    },
                    {
                        targets: 2,
                        responsivePriority: 1,
                        render: function (e, t, o, r) {
                            return (
                                '<div class="d-flex justify-content-start align-items-center user-name"><div class="avatar-wrapper"><div class="avatar me-2"><span class="avatar-initial rounded-circle bg-label-secondary"><i class="mdi mdi-bus"></i></span></div></div><div class="d-flex flex-column"><a class="text-heading fw-medium" href="app-logistics-fleet.html">VOL-' +
                                o.location +
                                "</a></div></div>"
                            );
                        },
                    },
                    {
                        targets: 3,
                        render: function (e, t, o, r) {
                            return (
                                '<div class="text-body">' +
                                o.start_city +
                                ", " +
                                o.start_country +
                                "</div >"
                            );
                        },
                    },
                    {
                        targets: 4,
                        render: function (e, t, o, r) {
                            return (
                                '<div class="text-body">' +
                                o.end_city +
                                ", " +
                                o.end_country +
                                "</div >"
                            );
                        },
                    },
                    {
                        targets: -2,
                        render: function (e, t, o, r) {
                            var o = o.warnings,
                                s = {
                                    1: {
                                        title: "No Warnings",
                                        class: "bg-label-success",
                                    },
                                    2: {
                                        title: "Temperature Not Optimal",
                                        class: "bg-label-warning",
                                    },
                                    3: {
                                        title: "Ecu Not Responding",
                                        class: "bg-label-danger",
                                    },
                                    4: {
                                        title: "Oil Leakage",
                                        class: "bg-label-info",
                                    },
                                    5: {
                                        title: "fuel problems",
                                        class: "bg-label-primary",
                                    },
                                };
                            return void 0 === s[o]
                                ? e
                                : '<span class="badge rounded-pill ' +
                                      s[o].class +
                                      '">' +
                                      s[o].title +
                                      "</span>";
                        },
                    },
                    {
                        targets: -1,
                        render: function (e, t, o, r) {
                            o = o.progress;
                            return (
                                '<div class="d-flex align-items-center"><div div class="progress w-100 rounded" style="height: 8px;"><div class="progress-bar" role="progressbar" style="width:' +
                                o +
                                '%;" aria-valuenow="' +
                                o +
                                '" aria-valuemin="0" aria-valuemax="100"></div></div><div class="text-body ms-3">' +
                                o +
                                "%</div></div>"
                            );
                        },
                    },
                ],
                order: [2, "asc"],
                dom: '<"table-responsive"t><"row d-flex align-items-center"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                displayLength: 5,
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
