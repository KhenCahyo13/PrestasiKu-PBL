<?php
    $title = 'PrestasiKu - Dashboard';
    ob_start();
?>
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
                            <p class="heading-4 font-semibold my-0">12</p>
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
                            <p class="heading-4 font-semibold my-0">10</p>
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
                            <p class="heading-4 font-semibold my-0">112</p>
                            <p class="text-sm text-secondary my-0">Total Achievement Data</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-6">
                <div class="px-3 py-3 rounded shadow-sm bg-white">
                    <div class="d-flex align-items-center gap-3">
                        <div class="px-4 py-4 rounded card-icon-primary">
                            <i class="fa-solid fa-graduation-cap" style="font-size: 32px;"></i>
                        </div>
                        <div class="d-flex flex-column">
                            <p class="heading-4 font-semibold my-0">756</p>
                            <p class="text-sm text-secondary my-0">Total Student Data</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="px-3 py-3 rounded shadow-sm bg-white">
                    <div class="d-flex flex-column gap-4">
                        <div class="d-flex flex-column">
                            <p class="heading-6 my-0">Total Achievement Based on Category</p>
                            <p class="text-sm text-secondary my-0">2023 January - 2023 December</p>
                        </div>
                        <div class="chart-container">
                            <canvas id="totalAchievementBasedOnCategoryChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-4 mt-3 mt-lg-0">
        <div class="row">
            <div class="col-12">
                <div class="px-3 py-3 rounded shadow-sm bg-white">
                    <div class="d-flex flex-column gap-4">
                        <div class="d-flex flex-column">
                            <p class="heading-6 my-0">Total Achievement Ranking <span class="text-primary">- Top 10</span></p>
                            <p class="text-sm text-secondary my-0">Total Student Achievement Ranking</p>
                        </div>
                        <div class="d-flex flex-column gap-2">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center gap-2">
                                    <p class="text-base text-secondary my-0">1.</p>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="rounded-profile-rank">
                                            <img src="<?= images('sample-profile1.png') ?>" alt="Profile Image">
                                        </div>
                                        <div class="d-flex flex-column">
                                            <p class="my-0 text-sm font-medium">Khen Muhammad Cahyo</p>
                                            <p class="my-0 text-xs text-secondary">Informatics Engineer - TI 2I</p>
                                        </div>
                                    </div>
                                </div>
                                <p class="heading-5 my-0 text-primary">6</p>
                            </div>
                        </div>
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