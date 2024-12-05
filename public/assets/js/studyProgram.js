$(document).ready(function() {
    // Init variables
    const alertMessageElement = $('#alertMessage');
    const showPerPagePagination = $('#showPerPagePagination');
    const prevButtonPagination = $('#prevButtonPagination');
    const nextButtonPagination = $('#nextButtonPagination');
    const searchStudyProgramInput = $('#searchStudyProgram');
    const createDepartmentIdSelectInput = $('#createDepartmentId');
    const updateDepartmentIdSelectInput = $('#updateDepartmentId');

    // Get and setup study programs table
    const fetchAndSetupStudyProgramsTable = (page = 1, limit = showPerPagePagination, search = '') => {
        const studyProgramsTableBody = $('#studyProgramsTableBody');

        studyProgramsTableBody.empty();

        $.ajax({
            url: `${BASE_API_URL}/study-programs?page=${page}&limit=${limit}&search=${search}`,
            method: 'GET',
            success: function(response) {
                $('#showPerPageTotal').text(response.pagination.items_per_page);
                $('#totalData').text(response.pagination.total_items);
                $('#currentPage').text(response.pagination.current_page);
                $('#totalPages').text(response.pagination.total_pages);
                for (let i = 0; i < response.data.length; i++) {
                    const studyProgram = response.data[i];
                    const studyProgramRow = `
                        <tr>
                            <td class="px-md-4 py-md-3 text-sm">${i + 1}</td>
                            <td class="px-md-4 py-md-3 text-sm">${studyProgram.studyprogram_name}</td>
                            <td class="px-md-4 py-md-3 text-sm">${studyProgram.department_name}</td>
                            <td class="px-md-4 py-md-3 text-sm">${formatDateToIndonesian(studyProgram.studyprogram_createdat)}</td>
                            <td class="px-md-4 py-md-3 text-sm">${formatDateToIndonesian(studyProgram.studyprogram_updatedat)}</td>
                            <td class="px-md-4 py-md-3 text-sm">
                                <div class="dropdown">
                                    <button class="btn btn-transparent d-block mx-auto p-0" id="actionsButton" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fa-solid fa-ellipsis"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="actionsButton">
                                        <li>
                                            <button type="button" class="dropdown-item d-flex align-items-center gap-2" data-id="${studyProgram.studyprogram_id}" data-name="${studyProgram.studyprogram_name}" data-department="${studyProgram.department_id}" data-bs-toggle="modal" data-bs-target="#updateStudyProgramModal" id="updateStudyProgramAction">
                                                <i class="fa-solid fa-edit text-secondary"></i> Update
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex align-items-center gap-2" data-id="${studyProgram.studyprogram_id}" data-bs-toggle="modal" data-bs-target="#deleteStudyProgramModal" id="deleteStudyProgramAction">
                                                <i class="fa-solid fa-trash text-secondary"></i> Delete
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    `;
                    studyProgramsTableBody.append(studyProgramRow);
                }

                // Delete study program button action
                document.querySelectorAll('#deleteStudyProgramAction').forEach((button) => {
                    button.addEventListener('click', function () {
                        const studyProgramId = this.getAttribute('data-id');
            
                        $('#deleteStudyProgramId').val(studyProgramId);
                    });
                });

                // Update study program button action
                document.querySelectorAll('#updateStudyProgramAction').forEach((button) => {
                    button.addEventListener('click', function () {
                        const studyProgramId = this.getAttribute('data-id');
                        const studyProgramName = this.getAttribute('data-name');
                        const departmentId = this.getAttribute('data-department');
            
                        $('#updateStudyProgramId').val(studyProgramId);
                        $('#updateStudyProgramName').val(studyProgramName);
                        $('#updateDepartmentId').val(departmentId);
                    });
                });
            },
            error: function() {
                console.log('Error while fetching study programs data!');
            }
        });
    };

    // Create a new study program
    const createStudyProgram = () => {
        const createStudyProgramForm = $('#createStudyProgramForm');
        const createStudyProgramModal = $('#createStudyProgramModal');
        const studyProgramName = $('#createStudyProgramName');
        const departmentId = $('#createDepartmentId');
        
        createStudyProgramForm.submit(function(event) {
            event.preventDefault();

            $('#createStudyProgramNameError').text('');
            $('#createDepartmentIdError').text('');

            let isValid = true;

            if (studyProgramName.val() === '') {
                $('#createStudyProgramNameError').text('Study program name is required');
                isValid = false;
            }

            if (departmentId.val() === '') {
                $('#createDepartmentIdError').text('Department name is required');
                isValid = false;
            }

            if (!isValid) {
                return false;
            }

            const data = {
                studyprogram_name: studyProgramName.val(),
                department_id: departmentId.val()
            };

            $.ajax({
                url: `${BASE_API_URL}/study-programs`,
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify(data),
                success: function(response) {
                    fetchAndSetupStudyProgramsTable(1, 5);
                    createStudyProgramModal.modal('hide');
                    createStudyProgramForm[0].reset();
                    alertMessageElement.html(`
                        <div class="my-2 alert alert-success alert-dismissible fade show" role="alert">
                            <p class="my-0 text-sm">
                                <strong>Success!</strong> ${response.message}
                            </p>
                            <button type="button" class="btn btn-close btn-sm" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    `);
                },
                error: function() {
                    createStudyProgramModal.modal('hide');
                    createStudyProgramForm[0].reset();
                    alertMessageElement.html(`
                        <div class="my-2 alert alert-danger alert-dismissible fade show" role="alert">
                            <p class="my-0 text-sm">
                                <strong>Failed!</strong> Department creation failed
                            </p>
                            <button type="button" class="btn btn-close btn-sm" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    `);
                }
            });
        });
    }

    // Update a study program
    const updateStudyProgramButton = $('#updateStudyProgramButton');
    updateStudyProgramButton.click(function() {
        const studyProgramId = $('#updateStudyProgramId').val();
        const departmentId = $('#updateDepartmentId').val();
        const studyProgramName = $('#updateStudyProgramName').val();
        const updateStudyProgramModal = $('#updateStudyProgramModal');

        const data = {
            studyprogram_name: studyProgramName,
            department_id: departmentId
        };

        $.ajax({
            url: `${BASE_API_URL}/study-programs/${studyProgramId}`,
            method: 'PATCH',
            contentType: 'application/json',
            data: JSON.stringify(data),
            success: function(response)  {
                updateStudyProgramModal.modal('hide');
                fetchAndSetupStudyProgramsTable(1, 5);
                alertMessageElement.html(`
                    <div class="my-2 alert alert-success alert-dismissible fade show" role="alert">
                        <p class="my-0 text-sm">
                            <strong>Success!</strong> ${response.message}
                        </p>
                        <button type="button" class="btn btn-close btn-sm" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                `);
            },
            error: function() {
                updateStudyProgramModal.modal('hide');
                alertMessageElement.html(`
                    <div class="my-2 alert alert-danger alert-dismissible fade show" role="alert">
                        <p class="my-0 text-sm">
                            <strong>Failed!</strong> Failed when update study program.
                        </p>
                        <button type="button" class="btn btn-close btn-sm" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                `);
            }
        });
    });

    // Delete a study program
    const deleteStudyProgramButton = $('#deleteStudyProgramButton');
    deleteStudyProgramButton.click(function() {
        const studyProgramId = $('#deleteStudyProgramId').val();
        const deleteStudyProgramModal = $('#deleteStudyProgramModal');

        $.ajax({
            url: `${BASE_API_URL}/study-programs/${studyProgramId}`,
            method: 'DELETE',
            success: function(response)  {
                deleteStudyProgramModal.modal('hide');
                fetchAndSetupStudyProgramsTable(1, 5);
                alertMessageElement.html(`
                    <div class="my-2 alert alert-success alert-dismissible fade show" role="alert">
                        <p class="my-0 text-sm">
                            <strong>Success!</strong> ${response.message}
                        </p>
                        <button type="button" class="btn btn-close btn-sm" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                `);
            },
            error: function() {
                deleteStudyProgramModal.modal('hide');
                alertMessageElement.html(`
                    <div class="my-2 alert alert-danger alert-dismissible fade show" role="alert">
                        <p class="my-0 text-sm">
                            <strong>Failed!</strong> Failed when delete study program.
                        </p>
                        <button type="button" class="btn btn-close btn-sm" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                `);
            }
        });
    });

    // Table DataTables
    showPerPagePagination.change(function() {
        const limit = $(this).val();

        fetchAndSetupStudyProgramsTable(1, limit);
    });

    prevButtonPagination.click(function() {
        const currentPage = parseInt($('#currentPage').text());

        if (currentPage > 1) {
            fetchAndSetupStudyProgramsTable(currentPage - 1, showPerPagePagination.val());
        }
    });

    nextButtonPagination.click(function() {
        const currentPage = parseInt($('#currentPage').text());
        const totalPages = parseInt($('#totalPages').text());

        if (currentPage < totalPages) {
            fetchAndSetupStudyProgramsTable(currentPage + 1, showPerPagePagination.val());
        }
    });

    let debounceTimeout;
    searchStudyProgramInput.keyup(function () {
        const search = $(this).val();
    
        clearTimeout(debounceTimeout);
    
        debounceTimeout = setTimeout(function () {
            fetchAndSetupStudyProgramsTable(1, showPerPagePagination.val(), search);
        }, 300);
    });

    // Setup study programs input
    $.ajax({
        url: `${BASE_API_URL}/departments?page=1&limit=100&search=`,
        method: 'GET',
        success: function(response) {
            for (let i = 0; i < response.data.length; i++) {
                const department = response.data[i];
                const departmentIdOptionInput = `
                    <option value="${department.department_id}">${department.department_name}</option>
                `;
                createDepartmentIdSelectInput.append(departmentIdOptionInput);
                updateDepartmentIdSelectInput.append(departmentIdOptionInput);
            }
        },
        error: function(response) {
            console.log('Error while fetching departments data!');
        }
    });

    // Run functions
    fetchAndSetupStudyProgramsTable(1, 5);
    createStudyProgram();
});