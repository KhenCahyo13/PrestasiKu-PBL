$(document).ready(function() {
    const alertMessageElement = $('#alertMessage');

    // Get Departments data and setup into departments table
    const fetchAndSetupDepartmentsTable = () => {
        const departmentsTableBody = $('#departmentsTableBody');
        let page = 1;
        let limit = 10;

        departmentsTableBody.empty();

        $.ajax({
            url: `${BASE_API_URL}/departments?page=${page}&limit=${limit}`,
            method: 'GET',
            success: function(response) {
                console.log(response.data);
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
                                            <a class="dropdown-item d-flex align-items-center gap-2" href="#">
                                                <i class="fa-solid fa-edit text-secondary"></i> Update
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item d-flex align-items-center gap-2" href="#">
                                                <i class="fa-solid fa-trash text-secondary"></i> Delete
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    `;
                    departmentsTableBody.append(departmentRow);
                }
            },
            error: function() {
                console.log('Error while fetching departments data!');
            }
        });
    };


    const createDepartment = () => {
        const createDepartmentForm = $('#createDepartmentForm');
        const createDepartmentModal = $('#createDepartmentModal');
        const departmentName = $('#department_name');
        
        createDepartmentForm.submit(function(event) {
            event.preventDefault();

            $('#departmentNameError').text('');

            let isValid = true;

            if (departmentName.val() === '') {
                $('#departmentNameError').text('Department name is required');
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
                    fetchAndSetupDepartmentsTable();
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

    // Run the functions
    fetchAndSetupDepartmentsTable();
    createDepartment();
});