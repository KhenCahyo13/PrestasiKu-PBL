<?php
$title = 'PrestasiKu - Achievement';
$pageTitle = 'Achievement';
$breadcrumbItems = [
    ['label' => 'Achievement', 'url' => '/PrestasiKu-PBL/web/achievement'],
    ['label' => 'Details', 'url' => '#'],
];
ob_start();
?>

<!-- Render Breadcrumb -->
<?php
renderComponent('breadcrumb', [
    'pageTitle' => $pageTitle,
    'breadcrumbItems' => $breadcrumbItems,
]);
?>

<div class="my-2" id="alertMessage"></div>
<section class="bg-white rounded shadow-sm">
    <div class="tab-container">
        <div class="tab-items">
            <div role="button" class="tab-item tab-item-active text-sm" id="btnInformationsTab">Informations</div>
            <div role="button" class="tab-item text-sm" id="btnFilesTab">Files</div>
        </div>
    </div>
    <div class="" id="informationTabContainer">
        <div class="px-4 py-3 border-bottom border-secondary">
            <p class="my-0 text-base font-semibold">Student Details</p>
        </div>
        <div class="px-4 py-3 gy-4 row" id="studentDetailsContainer"></div>
        <div class="px-4 py-3 border-bottom border-secondary">
            <p class="my-0 text-base font-semibold">Achievement Details</p>
        </div>
        <div class="px-4 py-3 gy-4 row" id="achievementDetailsContainer"></div>
        <div class="px-4 py-3 border-bottom border-secondary">
            <p class="my-0 text-base font-semibold">Verification Details</p>
        </div>
        <div class="px-4 py-3 gy-4 row" id="verificationDetailsContainer"></div>
    </div>
    <div class="d-none" id="filesTabContainer">
        <div class="px-4 py-3 border-bottom border-secondary">
            <p class="my-0 text-base font-semibold">Certificate & Letter of Assignment</p>
        </div>
        <div class="px-4 py-3 gy-4 row" id="fileDetailsContainer"></div>
    </div>
</section>
<script src="<?= js('achievement/details.js?v=' . time()) ?>"></script>

<?php
$content = ob_get_clean();
include layouts('main.php');
?>