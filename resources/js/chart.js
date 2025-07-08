document.addEventListener("DOMContentLoaded", function () {
    var apexChart = document.getElementById("chart");

    var chartOptions = {
        series: [
            {
                name: "Sales",
                data: [31, 40, 28, 51, 42, 82, 56],
            },
            {
                name: "Revenue",
                data: [11, 32, 45, 32, 34, 52, 41],
            },
            {
                name: "Customers",
                data: [15, 11, 32, 18, 9, 24, 11],
            },
        ],
        chart: {
            type: "area",
            height: 350,
            zoom: { enabled: false },
            toolbar: { show: false },
        },
        colors: ["#0d6efd", "#198754", "#ffc107"],
        dataLabels: { enabled: false },
        markers: { size: 4 },
        fill: {
            type: "gradient",
            gradient: {
                opacityFrom: 0.3,
                opacityTo: 0.1,
            },
        },
        stroke: { width: 2 },
        xaxis: {
            categories: ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"],
            title: { text: "Days" },
        },
        tooltip: {
            shared: true,
            intersect: false,
        },
        legend: {
            position: "top",
            horizontalAlign: "right",
        },
        responsive: [
            {
                breakpoint: 768,
                options: {
                    chart: { height: 300 },
                    legend: { position: "bottom" },
                },
            },
        ],
    };

    new ApexCharts(apexChart, chartOptions).render();
});
