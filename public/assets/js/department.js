$(document).ready(function() {
    const alertMessageElement = $('#alertMessage');
    const showPerPagePagination = $('#showPerPagePagination');
    const prevButtonPagination = $('#prevButtonPagination');
    const nextButtonPagination = $('#nextButtonPagination');
    const searchDepartmentInput = $('#searchDepartment');

    // Get and setup departments table
    const fetchAndSetupDepartmentsTable = (page = 1, limit = showPerPagePagination, search = '') => {
        const departmentsTableBody = $('#departmentsTableBody');

        departmentsTableBody.empty();

        $.ajax({
            url: `${BASE_API_URL}/departments?page=${page}&limit=${limit}&search=${search}`,
            method: 'GET',
            success: function(response) {
                $('#showPerPageTotal').text(response.pagination.items_per_page);
                $('#totalData').text(response.pagination.total_items);
                $('#currentPage').text(response.pagination.current_page);
                $('#totalPages').text(response.pagination.total_pages);
                for (let i = 0; i < response.data.length; i++) {
                    const department = response.data[i];
                    const departmentRow = `
                        <tr>
                            <td class="px-md-4 py-md-3 text-sm">${i + 1}</td>
                            <td class="px-md-4 py-md-3 text-sm">${department.department_name}</td>
                            <td class="px-md-4 py-md-3 text-sm">${formatDateToIndonesian(department.department_createdat)}</td>
                            <td class="px-md-4 py-md-3 text-sm">${formatDateToIndonesian(department.department_updatedat)}</td>
                            <td class="px-md-4 py-md-3 text-sm">
                                <div class="dropdown">
                                    <button class="btn btn-transparent d-block mx-auto p-0" id="actionsButton" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fa-solid fa-ellipsis"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="actionsButton">
                                        <li>
                                            <button type="button" class="dropdown-item d-flex align-items-center gap-2" data-id="${department.department_id}" data-name="${department.department_name}" data-bs-toggle="modal" data-bs-target="#updateDepartmentModal" id="updateDepartmentAction">
                                                <i class="fa-solid fa-edit text-secondary"></i> Update
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex align-items-center gap-2" data-id="${department.department_id}" data-bs-toggle="modal" data-bs-target="#deleteDepartmentModal" id="deleteDepartmentAction">
                                                <i class="fa-solid fa-trash text-secondary"></i> Delete
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    `;
                    departmentsTableBody.append(departmentRow);
                }

                // Delete department button action
                document.querySelectorAll('#deleteDepartmentAction').forEach((button) => {
                    button.addEventListener('click', function () {
                        const departmentId = this.getAttribute('data-id');
            
                        $('#deleteDepartmentId').val(departmentId);
                    });
                });

                // Update department button action
                document.querySelectorAll('#updateDepartmentAction').forEach((button) => {
                    button.addEventListener('click', function () {
                        const departmentId = this.getAttribute('data-id');
                        const departmentName = this.getAttribute('data-name');
            
                        $('#updateDepartmentId').val(departmentId);
                        $('#updateDepartmentName').val(departmentName);
                    });
                });
            },
            error: function() {
                console.log('Error while fetching departments data!');
            }
        });
    };

    // Create a new department
    const createDepartment = () => {
        const createDepartmentForm = $('#createDepartmentForm');
        const createDepartmentModal = $('#createDepartmentModal');
        const departmentName = $('#createDepartmentName');
        
        createDepartmentForm.submit(function(event) {
            event.preventDefault();

            $('#createDepartmentNameError').text('');

            let isValid = true;

            if (departmentName.val() === '') {
                $('#createDepartmentNameError').text('Department name is required');
                isValid = false;
            }

            if (!isValid) {
                return false;
            }

            const data = {
                department_name: departmentName.val()
            };

            $.ajax({
                url: `${BASE_API_URL}/departments`,
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify(data),
                success: function(response) {
                    fetchAndSetupDepartmentsTable(1, 5);
                    createDepartmentModal.modal('hide');
                    createDepartmentForm[0].reset();
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
                    createDepartmentModal.modal('hide');
                    createDepartmentForm[0].reset();
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

    // Update a department
    const updateDepartmentButton = $('#updateDepartmentButton');
    updateDepartmentButton.click(function() {
        const departmentId = $('#updateDepartmentId').val();
        const departmentName = $('#updateDepartmentName').val();
        const updateDepartmentModal = $('#updateDepartmentModal');

        const data = {
            department_name: departmentName
        };

        $.ajax({
            url: `${BASE_API_URL}/departments/${departmentId}`,
            method: 'PATCH',
            contentType: 'application/json',
            data: JSON.stringify(data),
            success: function(response)  {
                updateDepartmentModal.modal('hide');
                fetchAndSetupDepartmentsTable(1, 5);
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
                updateDepartmentModal.modal('hide');
                alertMessageElement.html(`
                    <div class="my-2 alert alert-danger alert-dismissible fade show" role="alert">
                        <p class="my-0 text-sm">
                            <strong>Failed!</strong> Department update failed
                        </p>
                        <button type="button" class="btn btn-close btn-sm" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                `);
            }
        });
    });

    // Delete a department
    const deleteDepartmentButton = $('#deleteDepartmentButton');
    deleteDepartmentButton.click(function() {
        const departmentId = $('#deleteDepartmentId').val();
        const deleteDepartmentModal = $('#deleteDepartmentModal');

        $.ajax({
            url: `${BASE_API_URL}/departments/${departmentId}`,
            method: 'DELETE',
            success: function(response)  {
                deleteDepartmentModal.modal('hide');
                fetchAndSetupDepartmentsTable(1, 5);
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
                deleteDepartmentModal.modal('hide');
                alertMessageElement.html(`
                    <div class="my-2 alert alert-danger alert-dismissible fade show" role="alert">
                        <p class="my-0 text-sm">
                            <strong>Failed!</strong> Department deletion failed
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

        fetchAndSetupDepartmentsTable(1, limit);
    });

    prevButtonPagination.click(function() {
        const currentPage = parseInt($('#currentPage').text());

        if (currentPage > 1) {
            fetchAndSetupDepartmentsTable(currentPage - 1, showPerPagePagination.val());
        }
    });

    nextButtonPagination.click(function() {
        const currentPage = parseInt($('#currentPage').text());
        const totalPages = parseInt($('#totalPages').text());

        if (currentPage < totalPages) {
            fetchAndSetupDepartmentsTable(currentPage + 1, showPerPagePagination.val());
        }
    });

    let debounceTimeout;
    searchDepartmentInput.keyup(function () {
        const search = $(this).val();
    
        clearTimeout(debounceTimeout);
    
        debounceTimeout = setTimeout(function () {
            fetchAndSetupDepartmentsTable(1, showPerPagePagination.val(), search);
        }, 300);
    });    

    // Run the functions
    fetchAndSetupDepartmentsTable(1, 5);
    createDepartment();
});