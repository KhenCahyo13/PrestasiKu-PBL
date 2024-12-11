$(document).ready(function() {
    // Variables
    const userRole = userRoleSessionValues;

    // Fetch and setup dashboard
    const fetchAndSetupDashboard = () => {
        $.ajax({
            url: `${BASE_API_URL}/dashboard`,
            method: 'GET',
            success: function(response) {
                const totalAchievementsPerMonthInOneYearData = response.data.find((item) => item.type == 'Chart Per Month in One Year');
                const totalAchievementsBasedOnScopeData = response.data.find((item) => item.type == 'Chart Based on Scope');
                const top10StudentAchievementData = response.data.find((item) => item.type == 'Chart Top 10 by Student');
                const totalBasedOnVerificationStatusData = response.data.find((item) => item.type == 'Total Achievement Based on Verification Status');

                // Setup Dashboard Elements
                setupTop10StudentAchievementData(top10StudentAchievementData);
                setupDashboardCard(totalBasedOnVerificationStatusData);
                if (userRole == 'Admin') {
                    setupTotalAchievementsBasedOnCategoryChart(totalAchievementsBasedOnScopeData);
                    setupTotalAchievementsPerMonthInOneYearChart(totalAchievementsPerMonthInOneYearData);
                }
            },
            error: function(response) {
                console.error('Error while fetching dashboard data');
            }
        });
    };

    // Setup Top 10 Student Achievement Data Element
    const setupTop10StudentAchievementData = (data) => {
        const top10StudentAchievementContainer = $('#top10StudentAchievementContainer');
        
        top10StudentAchievementContainer.empty();

        data.data.map((achievement, index) => {
            top10StudentAchievementContainer.append(`
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-2">
                        <p class="text-base text-secondary my-0 font-medium">${index + 1}.</p>
                        <div class="d-flex align-items-center">
                            <div class="rounded-profile-letter me-2">
                                <p class="heading-6 my-0 text-white">${achievement.detail_name[0]}</p>
                            </div>
                            <div class="d-flex flex-column">
                                <p class="my-0 text-sm font-medium">${achievement.detail_name}</p>
                                <p class="my-0 text-xs text-secondary">${achievement.studyprogram_name} - ${achievement.spclass_name}</p>
                            </div>
                        </div>
                    </div>
                    <p class="heading-5 my-0 text-primary">${achievement.total}</p>
                </div>
            `);
        });
    };

    // Setup Chart Total Achievements Based on Category
    const setupTotalAchievementsBasedOnCategoryChart = (data) => {
        const categories = data.data.map((item) => item.scope);
        const totalAchievements = data.data.map((item) => parseInt(item.total, 10));
    
        // Chart Data
        const chartData = {
            labels: categories,
            datasets: [{
                label: 'Achievements',
                data: totalAchievements,
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
            data: chartData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 24
                        }
                    }
                }
            }
        };

        // Render Chart
        const totalAchievementsBasedOnCategoryChartContainer = document.getElementById('totalAchievementsBasedOnCategoryChartContainer').getContext('2d');
        new Chart(totalAchievementsBasedOnCategoryChartContainer, config);
    };

    // Setup Chart Total Achievements per Month in One Year
    const setupTotalAchievementsPerMonthInOneYearChart = (data) => {
        const months = data.data.map((item) => item.month);
        const totalAchievements = data.data.map((item) => parseInt(item.total, 10));

        // Chart Data
        const chartData = {
            labels: months,
            datasets: [{
                label: 'Total Achievements',
                data: totalAchievements,
                borderColor: 'rgba(75, 192, 192, 1)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                fill: true,
                tension: 0.4,
                borderWidth: 2
            }]
        };

        // Chart Configuration
        const config = {
            type: 'line',
            data: chartData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        enabled: true
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true
                    },
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 5,
                            callback: function(value) {
                                return value % 1 === 0 ? value : '';
                            }
                        }
                    }
                }
            }
        };

        // Render Chart
        const ctx = document.getElementById('totalAchievementsPerMonthInOneYearChartContainer').getContext('2d');
        new Chart(ctx, config);
    };

    // Setup Dashboard Card
    const setupDashboardCard = (data) => {
        const totalAchievementsApprovedElement = $('#totalAchievementsApproved');
        const totalAchievementsRejectedElement = $('#totalAchievementsRejected');
        const totalAchievementsNotApprovedElement = $('#totalAchievementsNotApproved');
        const totalAchievementsDataElement = $('#totalAchievementsData');

        const totalAchievementsApproved = data.data.find((item) => item.status == 'Disetujui').total;
        const totalAchievementsRejected = data.data.find((item) => item.status == 'Ditolak').total;
        const totalAchievementsNotApproved = data.data.find((item) => item.status == 'Menunggu Persetujuan').total;
        const totalAchievementsData = Number(totalAchievementsApproved) + Number(totalAchievementsRejected) + Number(totalAchievementsNotApproved);

        totalAchievementsApprovedElement.text(totalAchievementsApproved);
        totalAchievementsRejectedElement.text(totalAchievementsRejected);
        totalAchievementsNotApprovedElement.text(totalAchievementsNotApproved);
        totalAchievementsDataElement.text(totalAchievementsData);
    };

    // Run the Functions
    fetchAndSetupDashboard();
});