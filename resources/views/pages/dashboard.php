<?php
    $title = 'PrestasiKu - Dashboard';
    ob_start();
?>
<script>
    const userRoleSessionValues = '<?= $_SESSION['user']['role'] ?>'
</script>
<div class="row">
    <div class="col-12 col-lg-8">
        <div class="row gy-3">
            <div class="col-12 col-lg-6">
                <div class="px-3 py-3 rounded shadow-sm bg-white">
                    <div class="d-flex align-items-center gap-3">
                        <div class="px-4 py-4 rounded card-icon-success">
                            <i class="fa-solid fa-medal" style="font-size: 32px;"></i>
                        </div>
                        <div class="d-flex flex-column">
                            <p class="heading-4 font-semibold my-0" id="totalAchievementsApproved">-</p>
                            <p class="text-sm text-secondary my-0">Achievements Approved</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-6">
                <div class="px-3 py-3 rounded shadow-sm bg-white">
                    <div class="d-flex align-items-center gap-3">
                        <div class="px-4 py-4 rounded card-icon-danger">
                            <i class="fa-solid fa-medal" style="font-size: 32px;"></i>
                        </div>
                        <div class="d-flex flex-column">
                            <p class="heading-4 font-semibold my-0" id="totalAchievementsRejected">-</p>
                            <p class="text-sm text-secondary my-0">Achievements Rejected</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-6">
                <div class="px-3 py-3 rounded shadow-sm bg-white">
                    <div class="d-flex align-items-center gap-3">
                        <div class="px-4 py-4 rounded card-icon-danger">
                            <i class="fa-solid fa-medal" style="font-size: 32px;"></i>
                        </div>
                        <div class="d-flex flex-column">
                            <p class="heading-4 font-semibold my-0" id="totalAchievementsNotApproved">-</p>
                            <p class="text-sm text-secondary my-0">Achievements Not Approved</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-6">
                <div class="px-3 py-3 rounded shadow-sm bg-white">
                    <div class="d-flex align-items-center gap-3">
                        <div class="px-4 py-4 rounded card-icon-primary">
                            <i class="fa-solid fa-medal" style="font-size: 32px;"></i>
                        </div>
                        <div class="d-flex flex-column">
                            <p class="heading-4 font-semibold my-0" id="totalAchievementsData">-</p>
                            <p class="text-sm text-secondary my-0">Total Achievements Data</p>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            if ($_SESSION['user']['role'] == 'Admin') {
            ?>
                <div class="col-12">
                    <div class="px-3 py-3 rounded shadow-sm bg-white">
                        <div class="d-flex flex-column gap-4">
                            <div class="d-flex flex-column">
                                <p class="heading-6 my-0">Total Achievements Based on Category</p>
                                <p class="text-sm text-secondary my-0">All time periods</p>
                            </div>
                            <div class="chart-container">
                                <canvas id="totalAchievementsBasedOnCategoryChartContainer"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="px-3 py-3 rounded shadow-sm bg-white">
                        <div class="d-flex flex-column gap-4">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex flex-column">
                                    <p class="heading-6 my-0">Total Achievements per Month in 1 Year</p>
                                    <p class="text-sm text-secondary my-0" id="yearAchievementPerMonthValue"></p>
                                </div>
                                <input type="date" class="form-control form-control-sm" id="filterYearAchievementPerMonth" style="width: fit-content;">
                            </div>
                            <div class="chart-container">
                                <canvas id="totalAchievementsPerMonthInOneYearChartContainer"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            <?php
            }
            ?>
        </div>
    </div>
    <div class="col-12 col-lg-4 mt-3 mt-lg-0">
        <div class="row">
            <div class="col-12">
                <div class="px-3 py-3 rounded shadow-sm bg-white">
                    <div class="d-flex flex-column gap-4">
                        <div class="d-flex flex-column">
                            <p class="heading-6 my-0">Total Achievements Ranking <span class="text-primary">- Top 10</span></p>
                            <p class="text-sm text-secondary my-0">Total Student Achievements Ranking</p>
                        </div>
                        <div class="d-flex flex-column gap-4" id="top10StudentAchievementContainer"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?= js('dashboard.js?v=' . time()) ?>"></script>
<?php
    $content = ob_get_clean();
    include layouts('main.php')
?>