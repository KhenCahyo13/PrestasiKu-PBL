$(document).ready(function() {
    const fetchAndSetupDepartmentsTable = () => {
        const departmentsTableBody = $('#departmentsTableBody');
        let page = 1;
        let limit = 10;

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

    fetchAndSetupDepartmentsTable();
});