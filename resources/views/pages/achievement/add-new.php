<?php
$title = 'PrestasiKu - Achievement';
$pageTitle = 'Achievement';
$breadcrumbItems = [
    ['label' => 'Achievement', 'url' => '/PrestasiKu-PBL/web/achievement'],
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
<div class="my-2" id="alertMessage"></div>
<!-- Card Form -->
<section class="bg-white rounded shadow-sm">
    <form action="#" method="POST" id="achievementForm">
        <div class="px-4 py-3 border-bottom border-secondary">
            <h2 class="my-0 text-base font-semibold">Achievement Informations</h2>
        </div>
        <div class="row gx-3 gy-2 px-4 py-3">
            <div class="col-12">
                <div class="d-flex flex-column gap-2">
                    <label for="achievementTitle" class="text-sm">Title <span class="text-danger">*</span></label>
                    <input type="text" class="form-control form-control-sm" placeholder="Enter your achievement title" id="achievementTitle">
                    <span class="text-xs text-danger" id="achievementTitleError"></span>
                </div>
            </div>
            <div class="col-12">
                <div class="d-flex flex-column gap-2">
                    <label for="achievementDescription" class="text-sm">Description <span class="text-danger">*</span></label>
                    <textarea id="achievementDescription" class="form-control form-control-sm" placeholder="Enter your achievement description" rows="6"></textarea>
                    <span class="text-xs text-danger" id="achievementDescriptionError"></span>
                </div>
            </div>
            <div class="col-12">
                <div class="d-flex flex-column gap-2">
                    <label for="achievementCategories" class="text-sm">Categories <span class="text-xs text-secondary">(Can more than 1) </span> <span class="text-danger">*</span></label>
                    <select id="achievementCategories" multiple class="form-control form-control-sm" aria-label="Achievement category multiple select"></select>
                    <span class="text-xs text-danger" id="achievementCategoriesError"></span>
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="d-flex flex-column gap-2">
                    <label for="achievementEventLocation" class="text-sm">Event Location <span class="text-danger">*</span></label>
                    <input type="text" class="form-control form-control-sm" placeholder="Eg. State Polytechnic of Malang" id="achievementEventLocation">
                    <span class="text-xs text-danger" id="achievementEventLocationError"></span>
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="d-flex flex-column gap-2">
                    <label for="achievementEventCity" class="text-sm">Event City <span class="text-danger">*</span></label>
                    <input type="text" class="form-control form-control-sm" placeholder="Eg. Malang" id="achievementEventCity">
                    <span class="text-xs text-danger" id="achievementEventCityError"></span>
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="d-flex flex-column gap-2">
                    <label for="achievementType" class="text-sm">Type <span class="text-danger">*</span></label>
                    <select id="achievementType" class="form-control form-control-sm">
                        <option value="">- Select achievement type</option>
                        <option value="Team">Team</option>
                        <option value="Individu">Individu</option>
                    </select>
                    <span class="text-xs text-danger" id="achievementTypeError"></span>
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="d-flex flex-column gap-2">
                    <label for="achievementScope" class="text-sm">Scope <span class="text-danger">*</span></label>
                    <select id="achievementScope" class="form-control form-control-sm">
                        <option value="">- Select achievement scope</option>
                        <option value="International">International</option>
                        <option value="National">National</option>
                        <option value="Regional">Regional</option>
                    </select>
                    <span class="text-xs text-danger" id="achievementScopeError"></span>
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="d-flex flex-column gap-2">
                    <label for="achievementEventStart" class="text-sm">Event Start <span class="text-danger">*</span></label>
                    <input type="date" class="form-control form-control-sm" id="achievementEventStart">
                    <span class="text-xs text-danger" id="achievementEventStartError"></span>
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="d-flex flex-column gap-2">
                    <label for="achievementEventEnd" class="text-sm">Event End <span class="text-danger">*</span></label>
                    <input type="date" class="form-control form-control-sm" id="achievementEventEnd">
                    <span class="text-xs text-danger" id="achievementEventEndError"></span>
                </div>
            </div>
        </div>
        <div class="px-4 py-3 border-bottom border-secondary">
            <div class="d-flex align-items-center justify-content-between">
                <h2 class="my-0 text-base font-semibold">Supervisor <span class="text-sm text-secondary font-medium">(Lecturer)</span></h2>
                <button type="button" class="btn btn-sm btn-dark" id="btnAddNewSupervisor">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fa-solid fa-plus"></i>
                        <span style="white-space: nowrap;">Add New</span>
                    </div>
                </button>
            </div>
        </div>
        <div class="row gx-3 gy-2 px-4 py-3" id="supervisorContainer">
            <div class="col-12 col-md-6" id="supervisorElement1">
                <div class="d-flex align-items-center gap-2">
                    <select id="supervisor1" class="form-control form-control-sm w-100">
                        <option value="">- Select lecturer</option>
                    </select>
                    <button type="button" class="btn btn-sm btn-danger" data-id="1">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </div>
                <span class="text-xs text-danger mt-2" id="supervisor1Error"></span>
            </div>
        </div>
        <div class="px-4 py-3 border-bottom border-secondary">
            <h2 class="my-0 text-base font-semibold">Supporting Files</h2>
        </div>
        <div class="row gx-3 gy-2 px-4 py-3">
            <div class="col-12 col-md-6">
                <div class="d-flex flex-column gap-2">
                    <label for="achievementCertificateFile" class="text-sm">Certificate <span class="text-danger">*</span></label>
                    <input type="file" class="form-control form-control-sm" id="achievementCertificateFile" accept=".pdf">
                    <span class="text-xs text-danger" id="achievementCertificateFileError"></span>
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="d-flex flex-column gap-2">
                    <label for="achievementAssignmentFile" class="text-sm">Letter of Assignment <span class="text-danger">*</span></label>
                    <input type="file" class="form-control form-control-sm" id="achievementAssignmentFile" accept=".pdf">
                    <span class="text-xs text-danger" id="achievementAssignmentFileError"></span>
                </div>
            </div>
        </div>
        <div class="px-3 py-3 mt-4 border-top border-secondary">
            <div class="d-flex align-items-center gap-2 justify-content-end">
                <a href="<?= url('achievement') ?>" class="btn btn-sm btn-danger">Cancel</a>
                <button type="submit" class="btn btn-sm btn-primary">Submit</button>
            </div>
        </div>
    </form>
</section>
<script src="<?= js('achievement/add-new.js?v=' . time()) ?>"></script>

<?php
$content = ob_get_clean();
include layouts('main.php');
?>