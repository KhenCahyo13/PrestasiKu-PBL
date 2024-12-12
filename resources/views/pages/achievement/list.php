<?php
$title = 'PrestasiKu - Achievement';
$pageTitle = 'Achievement';
$breadcrumbItems = [
    ['label' => 'Achievement', 'url' => '#'],
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

<!-- Card Achievement List -->
<section class="my-1 bg-white rounded shadow-sm">
    <div class="px-4 py-3 border-bottom border-secondary">
        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-md-between gap-3">
            <h2 class="my-0 heading-6">Achievement List</h2>
            <div class="d-flex align-items-center gap-2">
                <input type="text" placeholder="Search data ..." class="form-control form-control-sm" id="searchAchievement">
                <?php
                if ($_SESSION['user']['role'] == 'Student') {
                ?>
                    <a href="<?= url('achievement/add-new') ?>" class="btn btn-dark btn-sm">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fa-solid fa-plus"></i>
                            <span style="white-space: nowrap;">Add New</span>
                        </div>
                    </a>
                <?php
                }
                ?>
            </div>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead class="table-secondary">
                <tr>
                    <th class="px-md-4 py-md-3 text-sm font-medium">No</th>
                    <th class="px-md-4 py-md-3 text-sm font-medium">Title</th>
                    <th class="px-md-4 py-md-3 text-sm font-medium">Type</th>
                    <th class="px-md-4 py-md-3 text-sm font-medium">Scope</th>
                    <th class="px-md-4 py-md-3 text-sm font-medium text-center">Verification Status</th>
                    <th class="px-md-4 py-md-3 text-sm font-medium">Created at</th>
                    <th class="px-md-4 py-md-3 text-sm font-medium text-center">Actions</th>
                </tr>
            </thead>
            <tbody id="achievementsTableBody"></tbody>
        </table>
    </div>
    <div class="px-4 py-2">
        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-md-between gap-3">
            <p class="my-0 text-secondary text-xs">Showing <span class="font-medium" id="showPerPageTotal"></span> data out of <span class="font-medium" id="totalData"></span> - Page <span class="font-medium" id="currentPage"></span> from <span class="font-medium" id="totalPages"></span> pages</p>
            <div class="d-flex align-items-center gap-2">
                <p class="my-0 text-secondary text-xs">Show</p>
                <select class="form-select form-select-sm" id="showPerPagePagination">
                    <option value="5">5</option>
                    <option value="10">10</option>
                </select>
                <p class="my-0 text-secondary text-xs" style="white-space: nowrap">per page</p>
                <button class="btn btn-secondary btn-sm" type="button" id="prevButtonPagination">
                    <i class="fa-solid fa-chevron-left"></i>
                </button>
                <button class="btn btn-secondary btn-sm" type="button" id="nextButtonPagination">
                    <i class="fa-solid fa-chevron-right"></i>
                </button>
            </div>
        </div>
    </div>
</section>
<!-- Approver List Modal -->
<div class="modal fade" id="approverListModal" tabindex="-1" aria-labelledby="approverListModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header justify-content-between px-3 py-3">
                <p class="heading-6 my-0">Approver List</p>
                <button type="button" class="btn btn-transparent p-0" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="d-flex flex-column gap-3" id="approverListElement"></div>
            </div>
        </div>
    </div>
</div>
<!-- History Logs Modal -->
<div class="modal fade" id="historyLogsModal" tabindex="-1" aria-labelledby="historyLogsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header justify-content-between px-3 py-3">
                <p class="heading-6 my-0">History Logs</p>
                <button type="button" class="btn btn-transparent p-0" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="d-flex flex-column gap-3" id="historyLogsElement"></div>
            </div>
        </div>
    </div>
</div>
<script src="<?= js('achievement/list.js?v=' . time()) ?>"></script>

<?php
$content = ob_get_clean();
include layouts('main.php');
?>