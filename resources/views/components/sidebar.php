<aside class="d-none d-lg-flex sidebar shadow-sm rounded px-2 py-3" id="sidebarElement">
    <div class="d-flex flex-column">
        <p class="my-0 heading-5 text-primary text-center font-semibold">PrestasiKu</p>
        <div class="mt-4 sidebar-items">
            <a href="<?= url('dashboard') ?>" class="px-3 py-3 d-flex align-items-center gap-3 rounded sidebar-item sidebar-item-active">
                <i class="fa-solid fa-gauge-simple"></i>
                <p class="my-0 text-sm font-medium">Dashboard</p>
            </a>
            <a href="<?= url('achievement') ?>" class="px-3 py-3 d-flex align-items-center gap-3 rounded sidebar-item">
                <i class="fa-solid fa-medal"></i>
                <p class="my-0 text-sm font-medium">Achievement</p>
            </a>
            <?php
            $roleName = $_SESSION['user']['role'];

            if ($roleName == 'Admin') {
            ?>
                <div class="sidebar-dropdown">
                    <a href="#" class="px-3 py-3 d-flex justify-content-between align-items-center rounded sidebar-item" id="masterDropdownButton">
                        <div class="d-flex align-items-center gap-3">
                            <i class="fa-solid fa-database"></i>
                            <p class="my-0 text-sm font-medium">Master</p>
                        </div>
                        <i class="fa-solid fa-chevron-down" style="font-size: 12px;" id="masterDropdownIcon"></i>
                    </a>
                    <ul class="sidebar-dropdown-items" id="masterDropdown">
                        <li>
                            <a href="<?= url('master/user') ?>" class="sidebar-dropdown-item text-xs">User</a>
                        </li>
                        <li>
                            <a href="<?= url('master/department') ?>" class="sidebar-dropdown-item text-xs">Department</a>
                        </li>
                        <li>
                            <a href="<?= url('master/study-program') ?>" class="sidebar-dropdown-item text-xs">Study Program</a>
                        </li>
                        <li>
                            <a href="<?= url('master/sp-class') ?>" class="sidebar-dropdown-item text-xs">Class</a>
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