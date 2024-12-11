<?php
    $currentPath = $_SERVER['REQUEST_URI'];
?>

<aside class="d-none d-lg-flex sidebar shadow-sm rounded px-2 py-3" id="sidebarElement">
    <div class="d-flex flex-column">
        <p class="my-0 heading-5 text-primary text-center font-semibold">PrestasiKu</p>
        <div class="mt-4 sidebar-items">
            <a href="<?= url('dashboard') ?>" class="px-3 py-3 d-flex align-items-center gap-3 rounded sidebar-item <?= $currentPath == '/PrestasiKu-PBL/web/dashboard' ? 'sidebar-item-active' : '' ?>">
                <i class="fa-solid fa-gauge-simple"></i>
                <p class="my-0 text-sm font-medium">Dashboard</p>
            </a>
            <a href="<?= url('achievement') ?>" class="px-3 py-3 d-flex align-items-center gap-3 rounded sidebar-item <?= strpos($currentPath, '/PrestasiKu-PBL/web/achievement') !== false ? 'sidebar-item-active' : '' ?>">
                <i class="fa-solid fa-medal"></i>
                <p class="my-0 text-sm font-medium">Achievement</p>
            </a>
            <?php
            $roleName = $_SESSION['user']['role'];

            if ($roleName == 'Admin') {
            ?>
                <div class="sidebar-dropdown">
                    <a href="#" class="px-3 py-3 d-flex justify-content-between align-items-center rounded sidebar-item <?= strpos($currentPath, '/PrestasiKu-PBL/web/master') !== false ? 'sidebar-item-active' : '' ?>" id="masterDropdownButton">
                        <div class="d-flex align-items-center gap-3">
                            <i class="fa-solid fa-database"></i>
                            <p class="my-0 text-sm font-medium">Master</p>
                        </div>
                        <i class="fa-solid fa-chevron-down" style="font-size: 12px;" id="masterDropdownIcon"></i>
                    </a>
                    <ul class="sidebar-dropdown-items" id="masterDropdown">
                        <li>
                            <a href="<?= url('master/user') ?>" class="sidebar-dropdown-item text-xs <?= strpos($currentPath, '/PrestasiKu-PBL/web/master/user') !== false ? 'sidebar-dropdown-item-active' : '' ?>">User</a>
                        </li>
                        <li>
                            <a href="<?= url('master/department') ?>" class="sidebar-dropdown-item text-xs <?= strpos($currentPath, '/PrestasiKu-PBL/web/master/department') !== false ? 'sidebar-dropdown-item-active' : '' ?>">Department</a>
                        </li>
                        <li>
                            <a href="<?= url('master/study-program') ?>" class="sidebar-dropdown-item text-xs <?= strpos($currentPath, '/PrestasiKu-PBL/web/master/study-program') !== false ? 'sidebar-dropdown-item-active' : '' ?>">Study Program</a>
                        </li>
                        <li>
                            <a href="<?= url('master/sp-class') ?>" class="sidebar-dropdown-item text-xs <?= strpos($currentPath, '/PrestasiKu-PBL/web/master/sp-class') !== false ? 'sidebar-dropdown-item-active' : '' ?>">Class</a>
                        </li>
                    </ul>
                </div>
            <?php
            }
            ?>
        </div>
    </div>
    <div class="sidebar-quotes">
        <img src="<?= images('student1.png') ?>" alt="Profile Image" class="sidebar-quotes-image">
        <p class="my-0 text-xs text-center text-primary fst-italic">“Outstanding students are those who are not afraid to fail and never stop learning.”</p>
    </div>
</aside>