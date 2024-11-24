<?php
    $title = 'PrestasiKu - Login';
    ob_start();
?>
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
<?php
    $content = ob_get_clean();
    include dirname(__DIR__, 2) . '/templates/auth.php';
?>