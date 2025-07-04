(function () {
    "use strict";

    feather.replace({ "aria-hidden": "true" });

    var ctx = document.getElementById("myChart");
    var gradient = ctx.getContext("2d").createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, "rgba(0, 123, 255, 0.4)");
    gradient.addColorStop(1, "rgba(0, 123, 255, 0)");

    var myChart = new Chart(ctx, {
        type: "line",
        data: {
            labels: [
                "Sunday",
                "Monday",
                "Tuesday",
                "Wednesday",
                "Thursday",
                "Friday",
                "Saturday",
            ],
            datasets: [
                {
                    data: [15339, 21345, 18483, 24003, 23489, 24092, 12034],
                    tension: 0.4,
                    backgroundColor: gradient,
                    borderColor: "#007bff",
                    borderWidth: 3,
                    pointBackgroundColor: "#007bff",
                    fill: true,
                },
            ],
        },
        options: {
            responsive: true,
            interaction: {
                mode: "index",
                intersect: false,
            },
            scales: {
                y: {
                    beginAtZero: false,
                    ticks: {
                        color: "#6c757d"
                    },
                    grid: {
                        color: "rgba(0,0,0,0.05)"
                    }
                },
                x: {
                    ticks: {
                        color: "#6c757d"
                    },
                    grid: {
                        display: false
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: "#fff",
                    titleColor: "#007bff",
                    bodyColor: "#333",
                    borderColor: "#007bff",
                    borderWidth: 1,
                    padding: 10
                }
            }
        },
    });
})();
