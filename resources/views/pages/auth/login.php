<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
    <!-- Internal -->
    <link rel="stylesheet" href="<?= css('/typography.css') ?>">
    <link rel="stylesheet" href="<?= css('/form.css') ?>">
    <link rel="stylesheet" href="<?= css('/button.css') ?>">
    <link rel="stylesheet" href="<?= css('/auth.css') ?>">
</head>
<body>
    <main class="d-flex">
        <!-- Left Content -->
         <section class="left-content">

         </section>
        <!-- Right Content -->
        <section class="container px-5 right-content">
            <p class="my-0 text-primary heading-5">PrestasiKu</p>
            <div class="mt-5 d-flex flex-column gap-4">
                <div class="d-flex flex-column align-items-center">
                    <p class="my-0 heading-4">Welcome to <span class="text-primary">PrestasiKu</span></p>
                    <p class="my-0 text-base text-secondary">You must login first to continue on this app!</p>
                </div>
                <form action="#" method="POST">
                    <div class="d-flex flex-column gap-2">
                        <div class="d-flex flex-column gap-1">
                            <label for="user_username" class="text-sm">Username <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm" placeholder="Enter your username" id="user_username">
                        </div>
                        <div class="d-flex flex-column gap-1">
                            <label for="user_password" class="text-sm">Password <span class="text-danger">*</span></label>
                            <input type="password" class="form-control form-control-sm" placeholder="Enter your password" id="user_password">
                            <div class="d-flex justify-content-end">
                                <a href="#" class="my-0 text-sm text-primary">Forgot Password</a>
                            </div>
                        </div>
                        <button class="btn btn-primary mt-2">Login</button>
                    </div>
                </form>
                <p class="my-0 text-sm text-center">Don't have account? <a href="#" class="text-primary">Register</a></p>
            </div>
        </section>
    </main>
</body>
</html>