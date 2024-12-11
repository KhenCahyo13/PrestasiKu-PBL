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

<script>
    const userIdSessionValue = '<?= $_SESSION['user']['id']; ?>';
</script>
<div class="my-2" id="alertMessage"></div>
<!-- Card Details -->
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
<!-- Card Approval Actions -->
<section class="mt-2 bg-white rounded shadow-sm" id="actionsContainer"></section>
<!-- Approve Modal -->
<div class="modal fade" id="approveModal" tabindex="-1" aria-labelledby="approveModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <input type="text" id="deleteSpClassId" hidden>
                <p class="my-0 text-sm text-center">Are you sure to approve this achievement?</p>
            </div>
            <div class="modal-footer py-1">
                <button type="button" class="btn btn-warning btn-sm" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success btn-sm" id="btnApprove">Approve</button>
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
                <p class="my-0 text-sm text-center">Are you sure to reject this achievement?</p>
                <div class="mt-4 d-flex flex-column gap-2">
                    <label for="rejectNotes" class="text-sm">Notes <span class="text-danger">*</span></label>
                    <textarea id="rejectNotes" class="form-control form-control-sm" placeholder="Enter your notes" rows="6"></textarea>
                    <span class="text-xs text-danger" id="rejectNotesError"></span>
                </div>
            </div>
            <div class="modal-footer py-1">
                <button type="button" class="btn btn-warning btn-sm" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger btn-sm" id="btnReject">Reject</button>
            </div>
        </div>
    </div>
</div>
<script src="<?= js('achievement/details.js?v=' . time()) ?>"></script>

<?php
$content = ob_get_clean();
include layouts('main.php');
?>