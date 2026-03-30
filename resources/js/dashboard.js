document.addEventListener('DOMContentLoaded', function() {
    const refillsByDay = window.dashboardData || {};
    
    // Map to 7 days (Sun=1, Mon=2, ... Sat=7)
    const chartData = [1, 2, 3, 4, 5, 6, 7].map(day => refillsByDay[day] || 0);
    
    const chartElement = document.getElementById('mainChart');
    if (!chartElement) return;
    
    const ctx = chartElement.getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
            datasets: [{
                label: 'Daily Revenue (₱)',
                data: chartData,
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59, 130, 246, 0.05)',
                fill: true,
                tension: 0.3,
                borderWidth: 2,
                pointRadius: 4,
                pointBackgroundColor: '#3b82f6',
                pointBorderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    labels: {
                        font: { size: 12 },
                        color: '#64748b'
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: '#f1f5f9' },
                    ticks: {
                        font: { size: 10 },
                        callback: (v) => '₱' + v
                    }
                },
                x: {
                    grid: { display: false },
                    ticks: { font: { size: 10 } }
                }
            }
        }
    });
});
