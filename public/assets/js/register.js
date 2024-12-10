$(document).ready(function() {
    // Action buttons
    const nextButton = $('#nextButton');
    const previousButton = $('#previousButton');
    const submitButton = $('#submitButton');
    // Steps
    const accountStep = $('#accountStep');
    const personalInformationStep = $('#personalInformationStep');
    const personalInformationStepStudent = $('#personalInformationStepStudent');
    const personalInformationStepLecturer = $('#personalInformationStepLecturer');
    const successfulStep = $('#successfulStep');
    // Circle Steppers
    const accountStepper = $('#accountStepper');
    const personalInformationStepper = $('#personalInformationStepper');
    const successStepper = $('#successStepper');
    // Circle Steppers Title
    const personalInformationTitle = $('#personalInformationTitle');
    const successTitle = $('#successTitle');
    // Stepper Lines
    // const stepperLine1 = $('#stepperLine1');
    // const stepperLine2 = $('#stepperLine2');
    // Others Element
    const loginElement = $('#loginElement');
    // Data Input
    const roleIdSelectInput = $('#roleId');
    const spClassIdSelectInput = $('#spClassId');
    const departmentSelectInput = $('#departmentId');
    const username = $('#userUsername');
    const password = $('#userPassword');
    const roleId = $('#roleId');
    const studentDetailName = $('#studentDetailName');
    const studentDetailDateOfBirth = $('#studentDetailDateOfBirth');
    const studentDetailNim = $('#studentDetailNim');
    const studentDetailPhoneNumber = $('#studentDetailPhoneNumber');
    const studentDetailEmail = $('#studentDetailEmail');
    const lecturerDetailName = $('#lecturerDetailName');
    const lecturerDetailNip = $('#lecturerDetailNip');
    const lecturerDetailPhoneNumber = $('#lecturerDetailPhoneNumber');
    const lecturerDetailEmail = $('#lecturerDetailEmail');


    // Step Validations
    const accountStepValidation = () => {
        $('#userUsernameError').text('');
        $('#userPasswordError').text('');
        $('#roleIdError').text('');

        const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/;
        let isValid = true;

        if (username.val() === '') {
            $('#userUsernameError').text('Username is required');
            isValid = false;
        }

        if (password.val() === '') {
            $('#userPasswordError').text('Password is required');
            isValid = false;
        } else if (!passwordRegex.test(password.val())) {
            $('#userPasswordError').text('Password must contain at least one uppercase letter, one lowercase letter, one number, one special character, and be at least 8 characters long.');
            isValid = false;
        }

        if (roleId.val() === '') {
            $('#roleIdError').text('Role is required');
            isValid = false;
        }

        return isValid;
    };

    const personalInformationStepValidation = () => {
        const roleIdSelectInputValue = roleIdSelectInput.find(':selected');
        const roleName = roleIdSelectInputValue.data('rolename');

        let isValid = true;

        if (roleName === 'Student') {
            $('#studentDetailNameError').text('');
            $('#studentDetailDateOfBirthError').text('');
            $('#studentDetailNimError').text('');
            $('#studentDetailPhoneNumberError').text('');
            $('#studentDetailEmailError').text('');
            $('#spClassIdError').text();

            if (studentDetailName.val() === '') {
                $('#studentDetailNameError').text('Name is required');
                isValid = false;
            }

            if (studentDetailDateOfBirth.val() === '') {
                $('#studentDetailDateOfBirthError').text('Date of birth is required');
                isValid = false;
            }

            if (studentDetailNim.val() === '') {
                $('#studentDetailNimError').text('NIM is required');
                isValid = false;
            }

            if (studentDetailPhoneNumber.val() === '') {
                $('#studentDetailPhoneNumberError').text('Phone number is required');
                isValid = false;
            }

            if (studentDetailEmail.val() === '') {
                $('#studentDetailEmailError').text('Email is required');
                isValid = false;
            }

            if (spClassIdSelectInput.val() === '') {
                $('#spClassIdError').text('Class is required');
                isValid = false;
            }
        } else if (roleName === 'Lecturer') {
            $('#lecturerDetailNameError').text('');
            $('#lecturerDetailNipError').text('');
            $('#lecturerDetailPhoneNumberError').text('');
            $('#lecturerDetailEmailError').text('');
            $('#departmentIdError').text();

            if (lecturerDetailName.val() === '') {
                $('#lecturerDetailNameError').text('Name is required');
                isValid = false;
            }

            if (lecturerDetailNip.val() === '') {
                $('#lecturerDetailNipError').text('NIP is required');
                isValid = false;
            }

            if (lecturerDetailPhoneNumber.val() === '') {
                $('#lecturerDetailPhoneNumberError').text('Phone number is required');
                isValid = false;
            }

            if (lecturerDetailEmail.val() === '') {
                $('#lecturerDetailEmailError').text('Email is required');
                isValid = false;
            }

            if (departmentSelectInput.val() === '') {
                $('#departmentIdError').text('Department is required');
                isValid = false;
            }
        }

        return isValid;
    }

    // Stepper functions
    nextButton.on('click', function() {
        if (accountStepValidation()) {
            accountStep.addClass('d-none');
            accountStepper.addClass('stepper-completed');
            accountStepper.html('<i class="fi fi-br-check text-white" style="line-height: 0;"></i>');
            personalInformationStep.removeClass('d-none');
            
            const roleIdSelectInputValue = roleIdSelectInput.find(':selected');
            const roleName = roleIdSelectInputValue.data('rolename');
            
            if (roleName === 'Student') {
                personalInformationStepLecturer.addClass('d-none');
                personalInformationStepStudent.removeClass('d-none');
            } else if (roleName === 'Lecturer') {
                personalInformationStepStudent.addClass('d-none');
                personalInformationStepLecturer.removeClass('d-none');
            }
    
            personalInformationStepper.removeClass('text-secondary');
            personalInformationStepper.addClass('stepper-active');
            personalInformationStepper.addClass('text-primary');
            personalInformationTitle.removeClass('text-secondary');
            personalInformationTitle.addClass('text-primary');
            // stepperLine1.addClass('stepper-line-active');
        }
    });

    previousButton.on('click', function() {
        accountStep.removeClass('d-none');
        accountStepper.html('1');
        accountStepper.removeClass('stepper-completed');
        personalInformationStep.addClass('d-none');
        personalInformationStepper.addClass('text-secondary');
        personalInformationStepper.removeClass('stepper-active');
        personalInformationStepper.removeClass('text-primary');
        personalInformationTitle.addClass('text-secondary');
        personalInformationTitle.removeClass('text-primary');
    });

    submitButton.on('click', function() {
        if (personalInformationStepValidation()) {
            let data = {
                user_username: username.val(),
                user_password: password.val(),
                role_id: roleId.val()
            };

            const roleIdSelectInputValue = roleIdSelectInput.find(':selected');
            const roleName = roleIdSelectInputValue.data('rolename');

            if (roleName === 'Student') {
                data = {
                    ...data,
                    detail_name: studentDetailName.val(),
                    detail_dateofbirth: studentDetailDateOfBirth.val(),
                    detail_nim: studentDetailNim.val(),
                    detail_phonenumber: studentDetailPhoneNumber.val(),
                    detail_email: studentDetailEmail.val(),
                    spclass_id: spClassIdSelectInput.val()
                }
            } else if (roleName === 'Lecturer') {
                data = {
                    ...data,
                    detail_name: lecturerDetailName.val(),
                    detail_nip: lecturerDetailNip.val(),
                    detail_phonenumber: lecturerDetailPhoneNumber.val(),
                    detail_email: lecturerDetailEmail.val(),
                    department_id: departmentSelectInput.val()
                }
            }

            $.ajax({
                url: `${BASE_API_URL}/auth/register`,
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify(data),
                success: function(response) {
                    if (response.success) {
                        personalInformationStep.addClass('d-none');
                        personalInformationStepper.addClass('stepper-completed');
                        personalInformationStepper.html('<i class="fi fi-br-check text-white" style="line-height: 0;"></i>');
                        successfulStep.removeClass('d-none');
                        successStepper.removeClass('text-secondary');
                        successStepper.addClass('stepper-completed');
                        successStepper.html('<i class="fi fi-br-check text-white" style="line-height: 0;"></i>');
                        successTitle.addClass('text-primary');
                        loginElement.addClass('d-none');
                        // stepperLine2.addClass('stepper-line-active');

                        setTimeout(() => {
                            window.location.href = '/PrestasiKu-PBL/web/auth/login';
                        }, 3000);
                    } else {
                        console.log(response);
                    }
                },
                error: function(response) {
                    alert('Error while registering account!');
                    console.log(response);
                }
            })
        }
    });

    // Setup form input
    const setupSelectInput = () => {
        // Setup role select input
        $.ajax({
            url: `${BASE_API_URL}/roles?page=1&limit=5&search=`,
            method: 'GET',
            success: function(response) {
                for (let i = 0; i < response.data.length; i++) {
                    const role = response.data[i];

                    if (role.role_name !== 'Admin')  {
                        const roleIdOptionInput = `
                            <option value="${role.role_id}" data-rolename="${role.role_name}">${role.role_name}</option>
                        `;
                        roleIdSelectInput.append(roleIdOptionInput);
                    }
                }
            },
            error: function(response) {
                console.log('Error while fetching roles data!');
            }
        });
        // Setup class select input
        $.ajax({
            url: `${BASE_API_URL}/sp-classes?page=1&limit=100&search=`,
            method: 'GET',
            success: function(response) {
                for (let i = 0; i < response.data.length; i++) {
                    const spClass = response.data[i];
                    const spClassIdOptionInput = `
                        <option value="${spClass.spclass_id}">${spClass.studyprogram_name} - ${spClass.spclass_name}</option>
                    `;
                    spClassIdSelectInput.append(spClassIdOptionInput);
                }
            },
            error: function(response) {
                console.log('Error while fetching class data!');
            }
        });
        // Setup department select input
        $.ajax({
            url: `${BASE_API_URL}/departments?page=1&limit=100&search=`,
            method: 'GET',
            success: function(response) {
                for (let i = 0; i < response.data.length; i++) {
                    const department = response.data[i];
                    const departmentIdOptionInput = `
                        <option value="${department.department_id}">${department.department_name}</option>
                    `;
                    departmentSelectInput.append(departmentIdOptionInput);
                }
            },
            error: function(response) {
                console.log('Error while fetching department data!');
            }
        });
    };

    // Run the functions
    setupSelectInput();
});