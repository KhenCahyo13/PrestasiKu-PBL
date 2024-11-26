$(document).ready(function() {
    // Init Chart
    const data = {
        labels: ['International', 'National', 'Regional'],
        datasets: [{
            label: 'Achievements',
            data: [45, 25, 79],
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)'
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)'
            ],
            borderWidth: 1
        }]
    };

    // Chart Configuration
    const config = {
        type: 'doughnut',
        data: data,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 24
                    }
                },
            }
        }
    };

    // Render Chart
    const totalAchievementBasedOnCategoryChart = document.getElementById('totalAchievementBasedOnCategoryChart').getContext('2d');
    new Chart(totalAchievementBasedOnCategoryChart, config);
});