// COLORS

const root = document.documentElement;

const primaryColor = getComputedStyle(root)
    .getPropertyValue('--color-primary')
    .trim();

const secondaryColor = getComputedStyle(root)
    .getPropertyValue('--color-secondary')
    .trim();


// USER CHART

const userCanvas = document.getElementById("userChart");

const userChart = new Chart(userCanvas, {
    type: "doughnut",

    data: {
        labels: ["Students", "Staff", "Admin"],

        datasets: [{
            data: [0, 0, 0]
        }]
    },

    options: {
        responsive: true
    }
});


// ITEM REPORT PIE CHART

const itemReportCanvas =
    document.getElementById("itemReportChart");

const itemReportChart = new Chart(itemReportCanvas, {
    type: "pie",

    data: {
        labels: [
            "Loss Item Reports",
            "Claim Requests",
            "Surrender Reports"
        ],

        datasets: [{
            data: [0, 0, 0],

            backgroundColor: [
                "#FF6384",
                "#36A2EB",
                "#FFCE56"
            ]
        }]
    },

    options: {
        responsive: true,

        plugins: {
            datalabels: {
                color: secondaryColor,

                font: {
                    family: '"DM Sans", sans-serif',
                    weight: "450",
                    size: 14
                },

                anchor: "center",
                align: "center",

                formatter: (value, ctx) => {

                    const sum = ctx.dataset.data.reduce(
                        (a, b) => a + b,
                        0
                    );

                    if (sum === 0) {
                        return "";
                    }

                    const percentage =
                        "(" +
                        (value * 100 / sum).toFixed(1) +
                        "%)";

                    return [value, percentage];
                }
            },

            legend: {
                position: "bottom"
            }
        }
    },

    plugins: [ChartDataLabels]
});


// INVENTORY STATUS PIE CHART

const inventoryCanvas =
    document.getElementById("inventoryChart");

const inventoryChart = new Chart(inventoryCanvas, {
    type: "pie",

    data: {
        labels: [
            "In Storage",
            "Claimed",
            "Disposed"
        ],

        datasets: [{
            data: [0, 0, 0],

            backgroundColor: [
                "#32e8ee",
                "#2897e1",
                "#FFCE56"
            ]
        }]
    },

    options: {
        responsive: true,

        plugins: {
            datalabels: {
                color: secondaryColor,

                font: {
                    family: '"DM Sans", sans-serif',
                    weight: "450",
                    size: 14
                },

                formatter: (value, ctx) => {

                    const sum = ctx.dataset.data.reduce(
                        (a, b) => a + b,
                        0
                    );

                    if (sum === 0) {
                        return "";
                    }

                    const percentage =
                        "(" +
                        (value * 100 / sum).toFixed(1) +
                        "%)";

                    return [value, percentage];
                }
            },

            legend: {
                position: "bottom"
            }
        }
    },

    plugins: [ChartDataLabels]
});


// MONTHLY REPORTS LINE CHART

const lineCanvas = document.getElementById("lineChart");

const monthlyChart = new Chart(lineCanvas, {
    type: "line",

    data: {
        labels: [
            "Jan",
            "Feb",
            "Mar",
            "Apr",
            "May",
            "Jun",
            "Jul",
            "Aug",
            "Sep",
            "Oct",
            "Nov",
            "Dec"
        ],

        datasets: [{
            label: "Reports Per Month",

            data: [
                0, 0, 0, 0,
                0, 0, 0, 0,
                0, 0, 0, 0
            ],

            fill: false,
            borderColor: primaryColor,
            backgroundColor: "rgba(75,192,192,0.1)",
            tension: 0.1,
            borderWidth: 2
        }]
    },

    options: {
        responsive: true,

        plugins: {
            legend: {
                position: "top"
            }
        },

        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});


// LOST ITEM LOCATION CHART

const lostItemCanvas =
    document.getElementById("lostItemFreqChart");

const lostItemChart = new Chart(lostItemCanvas, {
    type: "bar",

    data: {
        labels: [],

        datasets: [{
            label: "Lost Item Frequency",
            data: [],
            borderWidth: 1,
            backgroundColor: "rgba(61, 87, 65, 0.2)",
            borderColor: "rgba(61, 87, 65, 0.8)"
        }]
    },

    options: {
        responsive: true,
        maintainAspectRatio: false,

        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});


// FILTER CONTROLS

const fromDateInput =
    document.getElementById("from-date");

const toDateInput =
    document.getElementById("to-date");

const resetButton =
    document.getElementById("resetFilterBtn");


fromDateInput.addEventListener(
    "change",
    applyDateFilter
);

toDateInput.addEventListener(
    "change",
    applyDateFilter
);

resetButton.addEventListener(
    "click",
    resetDashboard
);


// APPLY DATE FILTER

async function applyDateFilter() {

    if (
        !fromDateInput.value ||
        !toDateInput.value
    ) {
        return;
    }

    try {

        const response = await fetch(`../../controllers/DashboardController.php?from=${fromDateInput.value}&to=${toDateInput.value}`);

        const data = await response.json();

        if (data.success) {

            itemReportChart
                .data
                .datasets[0]
                .data = data.itemReports;

            itemReportChart.update();


            inventoryChart
                .data
                .datasets[0]
                .data = data.inventoryStatus;

            inventoryChart.update();


            document
                .getElementById("totalItems")
                .textContent = data.totalItems;

            document
                .getElementById("analyticsTotalItems")
                .textContent = data.totalItems;


            document
                .getElementById("totalReports")
                .textContent = data.totalReports;

            document
                .getElementById("analyticsTotalReports")
                .textContent = data.totalReports;


            lostItemChart.data.labels =
                data.locationLabels;

            lostItemChart
                .data
                .datasets[0]
                .data = data.locationData;

            lostItemChart.update();


            monthlyChart
                .data
                .datasets[0]
                .data = data.monthlyReports;

            monthlyChart.update();


            document
                .getElementById("claimSuccessRate")
                .textContent =
                data.claimSuccessRate +
                "% Success Rate";

            document
                .getElementById("claimSuccessDetails")
                .textContent =
                data.acceptedClaims +
                " / " +
                data.totalClaims +
                " Claim Requests Accepted";


            document
                .getElementById("itemDisposalRate")
                .textContent =
                data.itemDisposalRate +
                "% Items Disposed";

            document
                .getElementById("itemDisposalDetails")
                .textContent =
                data.disposedItems +
                " / " +
                data.totalDisposalItems +
                " Items Disposed";


            document
                .getElementById("lossMatchTime")
                .textContent =
                data.resolutionTimes.lossMatch +
                " days";

            document
                .getElementById("surrenderApprovalTime")
                .textContent =
                data.resolutionTimes.surrenderApproval +
                " days";

            document
                .getElementById("claimVerificationTime")
                .textContent =
                data.resolutionTimes.claimVerification +
                " days";

        }

    } catch (error) {

        console.error(
            "Dashboard Filter Error:",
            error
        );

    }

}


// LOAD DASHBOARD SUMMARY

async function loadDashboardSummary() {

    try {

        const response = await fetch("../../controllers/DashboardController.php");

        const data = await response.json();

        if (data.success) {

            document
                .getElementById("totalUsers")
                .textContent = data.totalUsers;

            document
                .getElementById("studentCount")
                .textContent = data.students;

            document
                .getElementById("staffCount")
                .textContent = data.staff;

            document
                .getElementById("adminCount")
                .textContent = data.admins;


            document
                .getElementById("analyticsTotalReports")
                .textContent = data.totalReports;

            document
                .getElementById("analyticsTotalItems")
                .textContent = data.totalItems;


            document
                .getElementById("totalReports")
                .textContent = data.totalReports;

            document
                .getElementById("totalItems")
                .textContent = data.totalItems;


            itemReportChart
                .data
                .datasets[0]
                .data = data.itemReports;

            itemReportChart.update();


            userChart
                .data
                .datasets[0]
                .data = [
                    data.students,
                    data.staff,
                    data.admins
                ];

            userChart.update();


            inventoryChart
                .data
                .datasets[0]
                .data = data.inventoryStatus;

            inventoryChart.update();


            monthlyChart
                .data
                .datasets[0]
                .data = data.monthlyReports;

            monthlyChart.update();


            lostItemChart.data.labels =
                data.locationLabels;

            lostItemChart
                .data
                .datasets[0]
                .data = data.locationData;

            lostItemChart.update();


            document
                .getElementById("lossMatchTime")
                .textContent =
                data.resolutionTimes.lossMatch +
                " days";

            document
                .getElementById("surrenderApprovalTime")
                .textContent =
                data.resolutionTimes.surrenderApproval +
                " days";

            document
                .getElementById("claimVerificationTime")
                .textContent =
                data.resolutionTimes.claimVerification +
                " days";


            document
                .getElementById("claimSuccessRate")
                .textContent =
                data.claimSuccessRate +
                "% Success Rate";

            document
                .getElementById("claimSuccessDetails")
                .textContent =
                data.acceptedClaims +
                " / " +
                data.totalClaims +
                " Claim Requests Accepted";


            document
                .getElementById("itemDisposalRate")
                .textContent =
                data.itemDisposalRate +
                "% Items Disposed";

            document
                .getElementById("itemDisposalDetails")
                .textContent =
                data.disposedItems +
                " / " +
                data.totalDisposalItems +
                " Items Disposed";

        }

    } catch (error) {

        console.error(
            "Dashboard Summary Error:",
            error
        );

    }

}


// RESET DASHBOARD

async function resetDashboard() {

    fromDateInput.value = "";
    toDateInput.value = "";

    await loadDashboardSummary();

}


// INITIAL LOAD

loadDashboardSummary();