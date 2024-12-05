<?php
$title = 'PrestasiKu - Master Class';
$pageTitle = 'Class';
$breadcrumbItems = [
    ['label' => 'Master', 'url' => '/'],
    ['label' => 'Class', 'url' => '#'],
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
            <h2 class="my-0 heading-6">Class List</h2>
            <div class="d-flex align-items-center gap-2">
                <input type="text" placeholder="Search data ..." class="form-control form-control-sm" id="searchInput">
                <button type="button" class="btn btn-dark btn-sm" data-bs-toggle="modal" data-bs-target="#createClassModal">
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
                    <th class="px-md-4 py-md-3 text-sm font-medium">Study Program Name</th>
                    <th class="px-md-4 py-md-3 text-sm font-medium">Class Name</th>
                    <th class="px-md-4 py-md-3 text-sm font-medium">Created at</th>
                    <th class="px-md-4 py-md-3 text-sm font-medium">Last Updated</th>
                    <th class="px-md-4 py-md-3 text-sm font-medium text-center">Actions</th>
                </tr>
            </thead>
            <tbody id="ClassTableBody">
                <tr>
                    <td class="px-md-4 py-md-3 text-sm">1</td>
                    <td class="px-md-4 py-md-3 text-sm">Teknologi Informasi</td>
                    <td class="px-md-4 py-md-3 text-sm">2I Teknik Informatika</td>
                    <td class="px-md-4 py-md-3 text-sm">25 January 2025</td>
                    <td class="px-md-4 py-md-3 text-sm">25 January 2025</td>
                    <td class="px-md-4 py-md-3 text-sm text-center">...</td>
                </tr>
                <tr>
                    <td class="px-md-4 py-md-3 text-sm">2</td>
                    <td class="px-md-4 py-md-3 text-sm">Teknik Sipil</td>
                    <td class="px-md-4 py-md-3 text-sm">3E Teknik Sipil</td>
                    <td class="px-md-4 py-md-3 text-sm">04 November 2024</td>
                    <td class="px-md-4 py-md-3 text-sm">05 November 2024</td>
                    <td class="px-md-4 py-md-3 text-sm text-center">...</td>
                </tr>
                <tr>
                    <td class="px-md-4 py-md-3 text-sm">3</td>
                    <td class="px-md-4 py-md-3 text-sm">Teknik Elektro</td>
                    <td class="px-md-4 py-md-3 text-sm">2G Sistem Kelistrikan</td>
                    <td class="px-md-4 py-md-3 text-sm">16 December 2024</td>
                    <td class="px-md-4 py-md-3 text-sm">18 December 2024</td>
                    <td class="px-md-4 py-md-3 text-sm text-center">...</td>
                </tr>
                <tr>
                    <td class="px-md-4 py-md-3 text-sm">4</td>
                    <td class="px-md-4 py-md-3 text-sm">Teknik Kimia</td>
                    <td class="px-md-4 py-md-3 text-sm">4A Teknologi Kimia Industri</td>
                    <td class="px-md-4 py-md-3 text-sm">1 March 2025</td>
                    <td class="px-md-4 py-md-3 text-sm">4 March 2025</td>
                    <td class="px-md-4 py-md-3 text-sm text-center">...</td>
                </tr>
                <tr>
                    <td class="px-md-4 py-md-3 text-sm">5</td>
                    <td class="px-md-4 py-md-3 text-sm">Teknik Mesin</td>
                    <td class="px-md-4 py-md-3 text-sm">3B Teknologi Pemeliharaan Pesawat Udara</td>
                    <td class="px-md-4 py-md-3 text-sm">26 June 2025</td>
                    <td class="px-md-4 py-md-3 text-sm">30 June 2025</td>
                    <td class="px-md-4 py-md-3 text-sm text-center">...</td>
                </tr>
            </tbody>
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

<!-- Create Class Modal -->
<div class="modal fade" id="createClassModal" tabindex="-1" aria-labelledby="createClassLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header justify-content-between px-3 py-3">
                <p class="heading-6 my-0" id="createClassLabel">Create Class</p>
                <button type="button" class="btn btn-transparent p-0" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <div class="modal-body">
                <form action="#" method="POST" id="createClassForm">
                    <div class="d-flex flex-column gap-2">
                        <div class="d-flex flex-column gap-2">
                            <label for="username" class="text-sm"> Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm" placeholder="Enter Class Name" id="createClassName">
                            <span class="text-xs text-danger" id="createClassNameError"></span>
                        </div>
                        <div class="d-flex flex-column gap-2">
                            <label for="username" class="text-sm"> Study Program <span class="text-danger">*</span></label>
                            <select id="" class="form-select form-select-sm">
                                <option value="1">Teknologi Informasi</option>
                                <option value="1">Teknik Kimia</option>
                                <option value="1">Teknik Elektro</option>
                            </select>
                            <span class="text-xs text-danger" id="creatStudyProgramIdError"></span>
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

<!-- Update Class Modal -->
<div class="modal fade" id="updateClassModal" tabindex="-1" aria-labelledby="updateClassModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header justify-content-center">
                <p class="my-0 text-sm text-center">Are you sure to update this Class data?</p>
            </div>
            <div class="modal-body">
                <div class="d-flex flex-column gap-3">
                    <input type="text" id="updateClassId" hidden>
                    <div class="d-flex flex-column gap-2">
                        <label for="username" class="text-sm"> Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-sm" placeholder="Enter Class Name" id="updateClassName">
                        <span class="text-xs text-danger" id="updateClassNameError"></span>
                    </div>
                </div>
            </div>
            <div class="modal-footer py-1">
                <button type="button" class="btn btn-warning btn-sm" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger btn-sm" id="updateClassButton">Update</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Class Modal -->
<div class="modal fade" id="deleteClassModal" tabindex="-1" aria-labelledby="deleteClassModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <input type="text" id="deleteCLassId" hidden>
                <p class="my-0 text-sm text-center">Are you sure to delete this Class data?</p>
            </div>
            <div class="modal-footer py-1">
                <button type="button" class="btn btn-warning btn-sm" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger btn-sm" id="deleteClassProgramButton">Delete</button>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include layouts('main.php');
?>