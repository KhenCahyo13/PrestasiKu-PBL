<?php
    $title = 'PrestasiKu - Master Departments';
    $pageTitle = 'Departments';
    $breadcrumbItems = [
        ['label' => 'Master', 'url' => '/'],
        ['label' => 'Departments', 'url' => '#'],
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
<section class="my-1 bg-white rounded shadow-sm overflow-hidden">
    <div class="px-4 py-2 border-bottom border-secondary">
        <div class="d-flex align-items-center justify-content-between">
            <h2 class="my-0 font-semibold heading-6">Departments List</h2>
            <div class="d-flex align-items-center gap-2">
                <input type="text" placeholder="Search data ..." class="form-control form-control-sm" id="searchInput">
                <button type="submit" class="btn btn-dark btn-sm">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fa-solid fa-plus"></i> 
                        <span style="white-space: nowrap;">Add New</span>
                    </div>
                </button>
            </div>
        </div>
    </div>
    <table class="table table-hover">
        <thead>
            <tr>
                <th class="px-4 py-3 text-sm font-medium">No</th>
                <th class="px-4 py-3 text-sm font-medium">Department Name</th>
                <th class="px-4 py-3 text-sm font-medium">Created at</th>
                <th class="px-4 py-3 text-sm font-medium">Last Updated</th>
                <th class="px-4 py-3 text-sm font-medium text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="px-4 py-3 text-sm">1</td>
                <td class="px-4 py-3 text-sm">Information Technology</td>
                <td class="px-4 py-3 text-sm">December, 24 2024, 14:00</td>
                <td class="px-4 py-3 text-sm">December, 24 2024, 14:00</td>
                <td class="px-4 py-3 text-sm">
                    <div class="dropdown">
                        <button class="btn btn-transparent d-block mx-auto p-0" id="actionsButton" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-solid fa-ellipsis text-sm"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileButton">
                            <li>
                                <a class="dropdown-item d-flex align-items-center gap-2" href="#">
                                    <i class="fa-solid fa-edit text-secondary"></i> Update
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item d-flex align-items-center gap-2" href="#">
                                    <i class="fa-solid fa-trash text-secondary"></i> Delete
                                </a>
                            </li>
                        </ul>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
    <div class="px-4 pt-2 pb-2">
        <div class="d-flex align-items-center justify-content-between">
            <p class="my-0 text-secondary text-xs">Showing <span class="font-medium">5</span> data out of <span class="font-medium">25</span></p>
            <div class="d-flex align-items-center gap-2">
                <p class="my-0 text-secondary text-xs">Show</p>
                <select class="form-select form-select-sm">
                    <option value="5">5</option>
                    <option value="10">10</option>
                </select>
                <p class="my-0 text-secondary text-xs" style="white-space: nowrap">per page</p>
                <button class="btn btn-secondary btn-sm" type="button">
                    <i class="fa-solid fa-chevron-left"></i>
                </button>
                <button class="btn btn-secondary btn-sm" type="button">
                    <i class="fa-solid fa-chevron-right"></i>
                </button>
            </div>
        </div>
    </div>
</section>

<?php
    $content = ob_get_clean();
    include layouts('main.php');
?>