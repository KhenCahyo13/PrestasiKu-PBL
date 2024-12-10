<?php
$title = 'PrestasiKu - User';
$pageTitle = 'User Verification';
$breadcrumbItems = [
    ['label' => 'Master', 'url' => '//PrestasiKu-PBL/web/dasshboard'],
    ['label' => 'User', 'url' => '/PrestasiKu-PBL/web/master/user'],
    ['label' => 'Verify', 'url' => '#'],
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
<div class="my-2" id="alertMessage"></div>
<section class="bg-white rounded shadow-sm">
    <div class="px-4 py-3 border-bottom border-secondary">
        <h2 class="my-0 heading-6">Account Details</h2>
    </div>
    <div class="px-4 py-3 gy-3 row" id="accountDetailsContainer"></div>
    <div class="px-4 py-3 border-bottom border-secondary">
        <h2 class="my-0 heading-6">User Details</h2>
    </div>
    <div class="px-4 py-3 gy-3 row" id="userDetailsContainer"></div>
</section>
<!-- Card Verification Actions -->
<section class="mt-2 bg-white rounded shadow-sm" id="actionsContainer"></section>
<!-- Approve Modal -->
<div class="modal fade" id="approveModal" tabindex="-1" aria-labelledby="approveModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <input type="text" id="deleteSpClassId" hidden>
                <p class="my-0 text-sm text-center">Are you sure to approve this user?</p>
            </div>
            <div class="modal-footer py-1">
                <button type="button" class="btn btn-warning btn-sm" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success btn-sm" id="approveButton">Approve</button>
            </div>
        </div>
    </div>
</div>
<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <input type="text" id="deleteSpClassId" hidden>
                <p class="my-0 text-sm text-center">Are you sure to reject this user?</p>
            </div>
            <div class="modal-footer py-1">
                <button type="button" class="btn btn-warning btn-sm" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger btn-sm" id="rejectButton">Reject</button>
            </div>
        </div>
    </div>
</div>
<script src="<?= js('user/verify.js?v=' . time()) ?>"></script>

<?php
$content = ob_get_clean();
include layouts('main.php');
?>