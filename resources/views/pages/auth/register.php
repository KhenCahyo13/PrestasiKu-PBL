<?php
    $title = 'PrestasiKu - Register';
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
            <p class="my-0 heading-4"><span class="text-primary">Ready</span> to Join Us?</p>
            <p class="my-0 text-base text-secondary text-center">Sign up for an account and record your achievements with us!</p>
        </div>
        <!-- Stepper -->
        <div class="stepper-container mt-3">
            <div class="d-flex flex-column align-items-center gap-2">
                <div class="stepper-circle heading-5 text-primary stepper-active" id="accountStepper">1</div>
                <p class="my-0 text-primary text-sm">Account</p>
            </div>
            <div class="stepper-line" id="stepperLine1"></div>
            <div class="d-flex flex-column align-items-center gap-2">
                <div class="stepper-circle heading-5 text-secondary" id="personalInformationStepper">2</div>
                <p class="my-0 text-secondary text-sm text-center" id="personalInformationTitle">Personal Information</p>
            </div>
            <div class="stepper-line" id="stepperLine2"></div>
            <div class="d-flex flex-column align-items-center gap-2">
                <div class="stepper-circle heading-5 text-secondary" id="successStepper">3</div>
                <p class="my-0 text-secondary text-sm" id="successTitle">Successful</p>
            </div>
        </div>
        <!-- Form Step -->
        <form action="#" method="POST" class="mt-3">
            <!-- Account Step form -->
            <div class="d-flex flex-column gap-3" id="accountStep">
                <div class="d-flex flex-column gap-2">
                    <label for="username" class="text-sm">Username <span class="text-danger">*</span></label>
                    <input type="text" class="form-control form-control-sm" placeholder="Enter your username" id="username">
                </div>
                <div class="d-flex flex-column gap-2">
                    <label for="password" class="text-sm">Password <span class="text-danger">*</span></label>
                    <input type="password" class="form-control form-control-sm" placeholder="Enter your password" id="password">
                </div>
                <div class="d-flex flex-column gap-2">
                    <label for="role" class="text-sm">Role <span class="text-danger">*</span></label>
                    <select id="role" class="form-control form-control-sm">
                        <option value="">- Select your role</option>
                        <option value="student">Student</option>
                        <option value="lecturer">Lecturer</option>
                    </select>
                </div>
                <button type="button" class="btn btn-primary mt-2" id="nextButton">Next</button>
            </div>
            <!-- Personal Information Step form -->
            <div class="d-flex flex-column gap-3 d-none" id="personalInformationStep">
                <div class="row gx-3 gy-3">
                    <div class="col-12 col-lg-6">
                        <div class="d-flex flex-column gap-2">
                            <label for="fullname" class="text-sm">Fullname <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm" placeholder="Enter your fullname" id="fullname">
                        </div>
                    </div>
                    <div class="col-12 col-lg-6">
                        <div class="d-flex flex-column gap-2">
                            <label for="nim" class="text-sm">NIM <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm" placeholder="Enter your nim" id="nim">
                        </div>
                    </div>
                    <div class="col-12 col-lg-6">
                        <div class="d-flex flex-column gap-2">
                            <label for="phoneNumber" class="text-sm">Phone Number <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm" placeholder="Enter your phone number" id="phoneNumber">
                        </div>
                    </div>
                    <div class="col-12 col-lg-6">
                        <div class="d-flex flex-column gap-2">
                            <label for="emailAddress" class="text-sm">Email Address <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm" placeholder="Enter your email address" id="emailAddress">
                        </div>
                    </div>
                </div>
                <div class="d-flex flex-column gap-2 mt-2">
                    <button type="button" class="btn btn-primary" id="submitButton">Submit</button>
                    <button type="button" class="btn btn-outline-primary" id="previousButton">Previous</button>
                </div>
            </div>
            <!-- Successful Step Form -->
             <div class="d-flex flex-column gap-3 d-none" id="successfulStep">
                <img src="<?= assets('icons/checkSuccess.png') ?>" alt="Check Success" class="d-block mx-auto" style="width: 96px;">
                <div class="d-flex flex-column gap-1">
                    <p class="my-0 text-center heading-6">Hooray, your account registration was successful!</p>
                    <p class="my-0 text-center text-sm text-secondary">Your account is being verified by the admin for correctness.</p>
                </div>
                <p class="my-0 text-center text-sm text-primary mt-5">You will be redirected to the login page in 3 seconds</p>
             </div>
        </form>
        <p class="my-0 text-sm text-center" id="loginElement">Already have account?  <a href="<?= url('auth/login') ?>" class="text-primary">Login</a></p>
    </div>
</section>

<script src="<?= js('/register.js') ?>"></script>
<?php
    $content = ob_get_clean();
    include dirname(__DIR__, 2) . '/layouts/auth.php';
?>