<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'PrestasiKu' ?></title>
    <!-- CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/255fd51aa4.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.js"></script>
    <!--Internal -->
    <link rel="stylesheet" href="<?= css('typography.css?v=' . time()) ?>">
    <link rel="stylesheet" href="<?= css('form.css?v=' . time()) ?>">
    <link rel="stylesheet" href="<?= css('button.css?v=' . time()) ?>">
    <link rel="stylesheet" href="<?= css('page.css?v=' . time()) ?>">
    <link rel="stylesheet" href="<?= css('sidebar.css?v=' . time()) ?>">
    <link rel="stylesheet" href="<?= css('navbar.css?v=' . time()) ?>">
    <link rel="stylesheet" href="<?= css('icon.css?v=' . time()) ?>">
    <link rel="stylesheet" href="<?= css('image.css?v=' . time()) ?>">
    <link rel="stylesheet" href="<?= css('chart.css?v=' . time()) ?>">
    <link rel="stylesheet" href="<?= css('dropdown.css?v=' . time()) ?>">
</head>
<body>
    <section class="px-3 py-3 d-flex gap-4" style="height: 100vh;">
        <?php
            include components('sidebar.php');
        ?>
        <div class="main-container">
            <?php
                include components('navbar.php');
            ?>
             <main class="content-container">
                <?= $content ?? '<p>Page component not found!</p>' ?>
             </main>
        </div>
    </section>

    <script>
        const BASE_API_URL = '<?php echo $_ENV['BASE_API_URL']; ?>';
        const redirectSuccessLogoutUrl = '<?= url('auth/login') ?>';
    </script>
    <script src="<?= js('navbar.js?v=' . time()) ?>"></script>
</body>
</html>