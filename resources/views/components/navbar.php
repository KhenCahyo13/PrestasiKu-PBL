<?php
    $fullname = $_SESSION['user']['fullname'];
    $first_letter = $fullname[0] ?? 'A';
?>

<nav class="navbar d-flex align-items-center justify-content-between px-3 py-2 shadow-sm rounded" style="background-color: white;" id="navbarElement">
    <div class="d-flex align-items-center gap-3">
        <button class="btn btn-transparent p-0" id="navbarButton">
            <i class="fa-solid fa-bars" style="font-size: 1rem;" id="navbarButtonIcon"></i>
        </button>
        <p class="my-0 text-sm font-medium d-none d-md-block">
            <?php
                if ($_SESSION['user']['role'] == 'Admin') {
                    echo 'PrestasiKu Admin as ';
                } else {
                    echo $_SESSION['user']['fullname'] . ' as ';
                }
            ?>
            <span class="text-primary">
                <?php
                    echo $_SESSION['user']['role'];
                ?>
            </span>
        </p>
    </div>
    <div class="d-flex align-items-center gap-3">
        <button class="btn btn-transparent p-0 rounded-icon" id="notificationButton">
            <i class="fa-regular fa-bell" style="font-size: 1rem;"></i>
        </button>
        <div class="dropdown">
            <button class="btn btn-transparent p-0" id="profileButton" data-bs-toggle="dropdown" aria-expanded="false">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-profile-letter">
                        <p class="heading-6 my-0"><?= $first_letter ?></p>
                    </div>
                    <i class="fa-solid fa-chevron-down" style="font-size: 10px;"></i>
                </div>
            </button>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileButton">
                <li>
                    <a class="dropdown-item d-flex align-items-center gap-2" href="#">
                        <i class="fa-solid fa-gear text-secondary"></i> Settings
                    </a>
                </li>
                <li>
                    <a class="dropdown-item d-flex align-items-center gap-2" href="#" id="logoutButton">
                        <i class="fa-solid fa-right-from-bracket text-secondary"></i> Logout
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>