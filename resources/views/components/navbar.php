<nav class="navbar d-flex align-items-center justify-content-between px-3 py-2 shadow-sm rounded" style="background-color: white;" id="navbarElement">
    <button class="btn btn-transparent p-0" id="navbarButton">
        <i class="fa-solid fa-bars" style="font-size: 1rem;" id="navbarButtonIcon"></i>
    </button>
    <div class="d-flex align-items-center gap-3">
        <button class="btn btn-transparent p-0 rounded-icon" id="notificationButton">
            <i class="fa-regular fa-bell" style="font-size: 1rem;"></i>
        </button>
        <button class="btn btn-transparent p-0" id="profileButton">
            <div class="d-flex align-items-center gap-2">
                <div class="rounded-profile">
                    <img src="<?= assets('/images/sample-profile1.png') ?>" alt="Profile Image">
                </div>
                <i class="fi fi-br-angle-down icon text-secondary" style="font-size: 10px;"></i>
            </div>
        </button>
    </div>
</nav>