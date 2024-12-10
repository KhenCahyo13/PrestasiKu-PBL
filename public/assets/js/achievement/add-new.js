$(document).ready(function() {
    // Form Elements
    const achievementForm = $('#achievementForm');
    // Container Elements
    const supervisorContainer = $('#supervisorContainer');
    // Button Elements
    const btnAddNewSupervisor = $('#btnAddNewSupervisor');
    // Input Elements
    const achievementTitle = $('#achievementTitle');
    const achievementDescription = $('#achievementDescription');
    const achievementEventLocation = $('#achievementEventLocation');
    const achievementEventCity = $('#achievementEventCity');
    const achievementType = $('#achievementType');
    const achievementScope = $('#achievementScope');
    const achievementCategoryId = $('#achievementCategoryId');
    const achievementEventStart = $('#achievementEventStart');
    const achievementEventEnd = $('#achievementEventEnd');
    const achievementCertificateFile = $('#achievementCertificateFile');
    const achievementAssignmentFile = $('#achievementAssignmentFile');
    const achievementCategories = $('#achievementCategories');
    // Alert Elements
    const alertMessageElement = $('#alertMessage');
    // Variables
    let supervisorCount = 2;

    // Remove Supervisor (Event Delegation)
    supervisorContainer.on('click', '.btn-danger', function () {
        const uniqueId = $(this).data('id');
        $(`#supervisorElement${uniqueId}`).remove();
    });

    // Add New Supervisor
    btnAddNewSupervisor.on('click', function() {
        const uniqueId = supervisorCount++;
        const supervisorElement = `
            <div class="col-12 col-md-6" id="supervisorElement${uniqueId}">
                <div class="d-flex align-items-center gap-2">
                    <select id="supervisor${uniqueId}" class="form-control form-control-sm w-100">
                        <option value="">- Select lecturer</option>
                    </select>
                    <button type="button" class="btn btn-sm btn-danger" data-id="${uniqueId}">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </div>
                <span class="text-xs text-danger mt-2" id="supervisor${uniqueId}Error"></span>
            </div>
        `;
        supervisorContainer.append(supervisorElement);

        const newSupervisorElement = $(`#supervisor${uniqueId}`);
        setupAchievementSupervisorsInput(newSupervisorElement);    
    });

    // Achievement Validations
    const validateSupervisors = () => {
        let isValid = true;
    
        supervisorContainer.find('select').each(function () {
            const supervisorId = $(this).attr('id');
            const errorElement = $(`#${supervisorId}Error`);
            errorElement.text('');
    
            if ($(this).val() === '') {
                errorElement.text(`Supervisor is required`);
                isValid = false;
            }
        });

        return isValid;
    }; 
    
    const achievementValidations = () => {
        $('#achievementTitleError').text('');
        $('#achievementDescriptionError').text('');
        $('#achievementEventLocationError').text('');
        $('#achievementEventCityError').text('');
        $('#achievementTypeError').text('');
        $('#achievementScopeError').text('');
        $('#achievementCategoryIdError').text('');
        $('#achievementEventStartError').text('');
        $('#achievementEventEndError').text('');
        $('#achievementCertificateFileError').text('');
        $('#achievementCertificateAssignmentError').text('');
        $('#achievementCategoriesError').text('');

        let isValid = true;

        if (achievementTitle.val() === '') {
            $('#achievementTitleError').text('Title is required');
            isValid = false;
        }

        if (achievementDescription.val() === '') {
            $('#achievementDescriptionError').text('Description is required');
            isValid = false;
        }

        if (achievementEventLocation.val() === '') {
            $('#achievementEventLocationError').text('Event location is required');
            isValid = false;
        }

        if (achievementEventCity.val() === '') {
            $('#achievementEventCityError').text('Event city is required');
            isValid = false;
        }

        if (achievementType.val() === '') {
            $('#achievementTypeError').text('Type is required');
            isValid = false;
        }

        if (achievementScope.val() === '') {
            $('#achievementScopeError').text('Scope is required');
            isValid = false;
        }

        if (achievementCategoryId.val() === '') {
            $('#achievementCategoryIdError').text('Category is required');
            isValid = false;
        }

        if (achievementEventStart.val() === '') {
            $('#achievementEventStartError').text('Event start is required');
            isValid = false;
        }

        if (achievementEventEnd.val() === '') {
            $('#achievementEventEndError').text('Event end is required');
            isValid = false;
        }

        if (achievementCertificateFile.val() === '') {
            $('#achievementCertificateFileError').text('Certificate file is required');
            isValid = false;
        }

        if (achievementAssignmentFile.val() === '') {
            $('#achievementAssignmentFileError').text('Assignment file is required');
            isValid = false;
        }

        if (!achievementCategories.val() || achievementCategories.val().length === 0) {
            $('#achievementCategoriesError').text('Categories is required');
            isValid = false;
        }

        const isSupervisorsValid = validateSupervisors();

        return isValid && isSupervisorsValid;
    };

    // Submit Achievement
    const submitAchievement = (data) => {
        $.ajax({
            url: `${BASE_API_URL}/achievements`,
            method: 'POST',
            data: data,
            processData: false,
            contentType: false,
            success: function(response) {
                achievementForm[0].reset();
                alertMessageElement.html(`
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <p class="my-0 text-sm">
                            <strong>Success!</strong> ${response.message}
                        </p>
                        <button type="button" class="btn btn-close btn-sm" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                `);
            },
            error: function(response) {
                alertMessageElement.html(`
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <p class="my-0 text-sm">
                            <strong>Failed!</strong> Failed while creating achievement.
                        </p>
                        <button type="button" class="btn btn-close btn-sm" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                `);
            }
        });
    };

    achievementForm.submit(function(event) {
        event.preventDefault();
        if (achievementValidations()) {
            let index = 0;
            const achievementFormData = new FormData();
    
            achievementFormData.append('achievement_title', achievementTitle.val());
            achievementFormData.append('achievement_description', achievementDescription.val());
            achievementFormData.append('achievement_type', achievementType.val());
            achievementFormData.append('achievement_eventlocation', achievementEventLocation.val());
            achievementFormData.append('achievement_eventcity', achievementEventCity.val());
            achievementFormData.append('achievement_eventstart', achievementEventStart.val());
            achievementFormData.append('achievement_eventend', achievementEventEnd.val());
            achievementFormData.append('achievement_scope', achievementScope.val());
            achievementFormData.append('category_id', achievementCategoryId.val());
    
            supervisorContainer.find('select').each(function () {
                const supervisorValue = $(this).val();
                achievementFormData.append(`approvers[${index}][user_id]`, supervisorValue);
                index++;
            });

            const selectedCategories = achievementCategories.val();
            selectedCategories.forEach((categoryId, index) => {
                achievementFormData.append(`categories[${index}][category_id]`, categoryId);
            });

            achievementFormData.append('files[0]', achievementCertificateFile[0].files[0]);
            achievementFormData.append('files[1]', achievementAssignmentFile[0].files[0]);         

            submitAchievement(achievementFormData);
        }
    });

    // Setup Inputs
    const setupAchievementInputs = () => {
        // Setup Achievement Categories
        $.ajax({
            url: `${BASE_API_URL}/achievement-categories?page=1&limit=100&search=`,
            method: 'GET',
            success: function(response) {
                for (let i = 0; i < response.data.length; i++) {
                    const category = response.data[i];
                    const categoryOptionInput = `
                        <option value="${category.category_id}">${category.category_name}</option>
                    `;
                    achievementCategories.append(categoryOptionInput);
                }
            },
            error: function(response) {
                console.log('Error while fetching achievement categories data!');
            }
        });
    };
    // Setup Achievement Supervisors (Lecturer)
    const setupAchievementSupervisorsInput = (element) => {
        $.ajax({
            url: `${BASE_API_URL}/users?page=1&limit=100&search=Lecturer`,
            method: 'GET',
            success: function(response) {
                for (let i = 0; i < response.data.length; i++) {
                    const lecturer = response.data[i];
                    const supervisorOptionInput = `
                        <option value="${lecturer.user_id}">${lecturer.lecturer_name} - NIP ${lecturer.lecturer_nip}</option>
                    `;
                    element.append(supervisorOptionInput);
                }
            },
            error: function(response) {
                console.log('Error while fetching lecturer data!');
            }
        });
    };

    supervisorContainer.find('select').each(function() {
        setupAchievementSupervisorsInput($(this));
    });

    // Run the Functions
    setupAchievementInputs();
});