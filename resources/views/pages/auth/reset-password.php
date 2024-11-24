<?php
    $title = 'PrestasiKu - Reset Password';
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
            <p class="my-0 heading-4">Reset Your <span class="text-primary">Password</span></p>
            <p class="my-0 text-base text-secondary">Create a more secure password for your account!</p>
        </div>
        <form action="#" method="POST">
            <div class="d-flex flex-column gap-2">
                <div class="d-flex flex-column gap-1">
                    <label for="password" class="text-sm">New Password <span class="text-danger">*</span></label>
                    <input type="password" class="form-control form-control-sm" placeholder="Enter your new password" id="password">
                </div>
                <button class="btn btn-primary mt-2">Reset Password</button>
            </div>
        </form>
    </div>
</section>
<?php
    $content = ob_get_clean();
    include dirname(__DIR__, 2) . '/templates/auth.php';
?>