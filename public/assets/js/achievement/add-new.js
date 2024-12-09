$(document).ready(function() {
    // container Elements
    const supervisorContainer = $('#supervisorContainer');
    // Button Elements
    const btnAddNewSupervisor = $('#btnAddNewSupervisor');
    const btnSubmitAchievement = $('#btnSubmitAchievement');
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
                        <option value="International">International</option>
                        <option value="National">National</option>
                        <option value="Regional">Regional</option>
                    </select>
                    <button type="button" class="btn btn-sm btn-danger" data-id="${uniqueId}">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </div>
                <span class="text-xs text-danger mt-2" id="supervisor${uniqueId}Error"></span>
            </div>
        `;
        supervisorContainer.append(supervisorElement);
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

        const isSupervisorsValid = validateSupervisors();

        return isValid && isSupervisorsValid;
    };

    btnSubmitAchievement.click(function() {
        if (achievementValidations()) {
            let index = 0;
            const achievementFormData = new FormData();
    
            achievementFormData.append('achievement_title', achievementTitle);
            achievementFormData.append('achievement_description', achievementDescription);
            achievementFormData.append('achievement_type', achievementType);
            achievementFormData.append('achievement_eventlocation', achievementEventLocation);
            achievementFormData.append('achievement_eventcity', achievementEventCity);
            achievementFormData.append('achievement_eventstart', achievementEventStart);
            achievementFormData.append('achievement_eventend', achievementEventEnd);
            achievementFormData.append('achievement_scope', achievementScope);
            achievementFormData.append('category_id', achievementCategoryId);
            achievementFormData.append('files[0]', achievementCertificateFile);
            achievementFormData.append('files[1]', achievementAssignmentFile);
    
            supervisorContainer.find('select').each(function () {
                const supervisorValue = $(this).val();
                achievementFormData.append(`approvers[${index}][user_id]`, supervisorValue);
                index++;
            });
        }
    });    
});