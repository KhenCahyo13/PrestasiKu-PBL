<?php
$title = 'PrestasiKu - Master Study Program';
$pageTitle = 'Study Program';
$breadcrumbItems = [
    ['label' => 'Master', 'url' => '/'],
    ['label' => 'Study Program', 'url' => '#'],
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

<!-- Datatable -->
<section class="my-1 bg-white rounded shadow-sm">
    <div class="px-4 py-3 border-bottom border-secondary">
        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-md-between gap-3">
            <h2 class="my-0 heading-6">Study Program List</h2>
            <div class="d-flex align-items-center gap-2">
                <input type="text" placeholder="Search data ..." class="form-control form-control-sm" id="searchStudyProgram">
                <button type="button" class="btn btn-dark btn-sm" data-bs-toggle="modal" data-bs-target="#createStudyProgramModal">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fa-solid fa-plus"></i>
                        <span style="white-space: nowrap;">Add New</span>
                    </div>
                </button>
            </div>
        </div>
    </div>
    <div class="px-4" id="alertMessage"></div>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead class="table-secondary">
                <tr>
                    <th class="px-md-4 py-md-3 text-sm font-medium">No</th>
                    <th class="px-md-4 py-md-3 text-sm font-medium">Name</th>
                    <th class="px-md-4 py-md-3 text-sm font-medium">Department</th>
                    <th class="px-md-4 py-md-3 text-sm font-medium">Created at</th>
                    <th class="px-md-4 py-md-3 text-sm font-medium">Last Updated</th>
                    <th class="px-md-4 py-md-3 text-sm font-medium text-center">Actions</th>
                </tr>
            </thead>
            <tbody id="studyProgramsTableBody"></tbody>
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

<!-- Create Study Program Modal -->
<div class="modal fade" id="createStudyProgramModal" tabindex="-1" aria-labelledby="createStudyProgramLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header justify-content-between px-3 py-3">
                <p class="heading-6 my-0" id="createStudyProgramLabel">Create Study Program</p>
                <button type="button" class="btn btn-transparent p-0" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <div class="modal-body">
                <form action="#" method="POST" id="createStudyProgramForm">
                    <div class="d-flex flex-column gap-2">
                        <div class="d-flex flex-column gap-2">
                            <label for="username" class="text-sm">Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm" placeholder="Enter study program name" id="createStudyProgramName">
                            <span class="text-xs text-danger" id="createStudyProgramNameError"></span>
                        </div>
                        <div class="d-flex flex-column gap-2">
                            <label for="username" class="text-sm">Department <span class="text-danger">*</span></label>
                            <select id="createDepartmentId" class="form-select form-select-sm">
                                <option value="">- Select department</option>
                            </select>
                            <span class="text-xs text-danger" id="createDepartmentIdError"></span>
                        </div>
                        <div class="d-flex flex-column gap-2 mt-1">
                            <button type="submit" class="btn btn-primary">Create</button>
                            <button type="reset" class="btn btn-outline-primary">Reset</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Update Study Program Modal -->
<div class="modal fade" id="updateStudyProgramModal" tabindex="-1" aria-labelledby="updateStudyProgramModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header justify-content-center">
                <p class="my-0 text-sm text-center">Are you sure to update this Study Program data?</p>
            </div>
            <div class="modal-body">
                <div class="d-flex flex-column gap-3">
                    <input type="text" id="updateStudyProgramId" hidden>
                    <div class="d-flex flex-column gap-2">
                        <label for="username" class="text-sm">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-sm" placeholder="Enter Study Program name" id="updateStudyProgramName">
                        <span class="text-xs text-danger" id="updateStudyProgramNameError"></span>
                    </div>
                    <div class="d-flex flex-column gap-2">
                            <label for="username" class="text-sm">Department <span class="text-danger">*</span></label>
                            <select id="updateDepartmentId" class="form-select form-select-sm">
                                <option value="">- Select department</option>
                            </select>
                            <span class="text-xs text-danger" id="updateDepartmentIdError"></span>
                        </div>
                </div>
            </div>
            <div class="modal-footer py-1">
                <button type="button" class="btn btn-warning btn-sm" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger btn-sm" id="updateStudyProgramButton">Update</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Study Program Modal -->
<div class="modal fade" id="deleteStudyProgramModal" tabindex="-1" aria-labelledby="deleteStudyProgramModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <input type="text" id="deleteStudyProgramId" hidden>
                <p class="my-0 text-sm text-center">Are you sure to delete this study program data?</p>
            </div>
            <div class="modal-footer py-1">
                <button type="button" class="btn btn-warning btn-sm" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger btn-sm" id="deleteStudyProgramButton">Delete</button>
            </div>
        </div>
    </div>
</div>
<script src="<?= js('studyProgram.js?v=' . time()) ?>"></script>

<?php
$content = ob_get_clean();
include layouts('main.php');
?>