<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PrestasiKu - Dashboard</title>
    <!-- CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/255fd51aa4.js" crossorigin="anonymous"></script>
    <!--Internal -->
    <link rel="stylesheet" href="<?= css('/typography.css?v=' . time()) ?>">
    <link rel="stylesheet" href="<?= css('/form.css?v=' . time()) ?>">
    <link rel="stylesheet" href="<?= css('/button.css?v=' . time()) ?>">
    <link rel="stylesheet" href="<?= css('/page.css?v=' . time()) ?>">
    <link rel="stylesheet" href="<?= css('/sidebar.css?v=' . time()) ?>">
    <link rel="stylesheet" href="<?= css('/icon.css?v=' . time()) ?>">
    <link rel="stylesheet" href="<?= css('/image.css?v=' . time()) ?>">
</head>
<body>
    <section class="row page-container">
        <div class="d-none d-lg-block col-lg-2" id="sidebarElement">
            <aside class="sidebar shadow-sm rounded px-2 py-2">
                <!-- Sidebar Brand -->
                <p class="my-0 heading-5 text-primary text-center font-semibold">PrestasiKu</p>
                <div class="mt-5 sidebar-items">
                    <a href="#" class="px-3 py-3 d-flex align-items-center gap-3 rounded sidebar-item sidebar-item-active">
                        <i class="fa-solid fa-gauge-simple"></i>
                        <p class="my-0 text-sm font-medium">Dashboard</p>
                    </a>
                    <a href="#" class="px-3 py-3 d-flex align-items-center gap-3 rounded sidebar-item">
                        <i class="fa-solid fa-medal"></i>
                        <p class="my-0 text-sm font-medium">Achievement</p>
                    </a>
                    <a href="#" class="px-3 py-3 d-flex align-items-center gap-3 rounded sidebar-item">
                        <i class="fa-solid fa-database"></i>
                        <p class="my-0 text-sm font-medium">Master</p>
                    </a>
                </div>
            </aside>
        </div>
        <div class="col-12 col-lg-10" id="navbarElement">
            <nav class="d-flex align-items-center justify-content-between px-3 py-2 shadow-sm rounded" style="background-color: white;">
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
        </div>
    </section>

    <script>
        $(document).ready(function() {
            const navbarButton = $('#navbarButton');
            const navbarButtonIcon = $('#navbarButtonIcon');
            const sidebarElement = $('#sidebarElement');
            const navbarElement = $('#navbarElement');

            navbarButton.click(function() {
                sidebarElement.toggleClass('d-none col-6');
                navbarElement.toggleClass('col-6 col-12');
                navbarButtonIcon.toggleClass('fa-xmark')
            });
        });
    </script>
</body>
</html>