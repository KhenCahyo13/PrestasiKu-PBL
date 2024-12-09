<?php
$title = 'PrestasiKu - Achievement';
$pageTitle = 'Achievement';
$breadcrumbItems = [
    ['label' => 'Master', 'url' => '//PrestasiKu-PBL/web/dasshboard'],
    ['label' => 'Achievement', 'url' => '/PrestasiKu-PBL/web/master/achievement'],
    ['label' => 'Add New', 'url' => '#'],
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
<!-- Card Details Informations -->
<section class="mt-1 bg-white rounded shadow-sm">
    <div class="px-4 py-3 border-bottom border-secondary">
        <h2 class="my-0 heading-6">Account Details</h2>
    </div>
    <div class="px-4 py-3 gy-3 row" id="accountDetailsContainer"></div>
    <div class="px-4 py-3 border-bottom border-secondary">
        <h2 class="my-0 heading-6">User Details</h2>
    </div>
    <div class="px-4 py-3 gy-3 row" id="userDetailsContainer"></div>
</section>

<?php
$content = ob_get_clean();
include layouts('main.php');
?>