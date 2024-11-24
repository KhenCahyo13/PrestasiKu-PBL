<?php
    $title = 'PrestasiKu - Forgot Password';
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
            <p class="my-0 heading-4">Forgot Your <span class="text-primary">Password?</span></p>
            <p class="my-0 text-base text-secondary">Enter your email address to reset your password.</p>
        </div>
        <form action="#" method="POST">
            <div class="d-flex flex-column gap-2">
                <div class="d-flex flex-column gap-1">
                    <label for="emailAddress" class="text-sm">Email Address <span class="text-danger">*</span></label>
                    <input type="text" class="form-control form-control-sm" placeholder="Enter your email address" id="emailAddress">
                </div>
                <button class="btn btn-primary mt-2">Send Email</button>
            </div>
        </form>
        <p class="my-0 text-sm text-center">Don't have account? <a href="#" class="text-primary">Register</a></p>
    </div>
</section>
<?php
    $content = ob_get_clean();
    include dirname(__DIR__, 2) . '/templates/auth.php';
?>