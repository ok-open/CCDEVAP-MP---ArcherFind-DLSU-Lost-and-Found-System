/**
 * Reusable Lost Item Frequency Chart
 * Used by both ../../pages/admin/admin_dashboard.html and student_dashboard.html
 * 
 * Usage:
 * - Include this file in your HTML
 * - Call initLostItemChart(canvasId, chartLabel, options)
 * - Uses shared location data across all instances
 * 
 * Example:
 * initLostItemChart('myChart', 'Lost Item Frequency')
 */

function initLostItemChart(canvasId, chartLabel, customOptions = {}) {
    const ctx = document.getElementById(canvasId);
    
    if (!ctx) {
        console.error(`Canvas element with ID '${canvasId}' not found`);
        return;
    }

    // Default chart options
    const defaultOptions = {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    };

    // Merge custom options with defaults
    const options = { ...defaultOptions, ...customOptions };

    // Create the chart with shared location data
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: customOptions.labels || [],
            datasets: [{
                label: chartLabel,
                data: customOptions.data || [],
                borderWidth: 1,
                backgroundColor: 'rgba(61, 87, 65, 0.2)',
                borderColor: 'rgba(61, 87, 65, 0.8)'
            }]
        },
        options: options
    });
}
