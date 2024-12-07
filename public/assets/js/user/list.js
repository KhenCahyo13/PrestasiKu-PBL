$(document).ready(function () {
    const showPerPagePagination = $('#showPerPagePagination');
    const prevButtonPagination = $('#prevButtonPagination');
    const nextButtonPagination = $('#nextButtonPagination');
    const searchUserInput = $('#searchUser');

    const fetchAndSetupUsersTable = (page = 1, limit = showPerPagePagination, search = '') => {
        const userTableBody = $('#userTableBody');

        userTableBody.empty();

        $.ajax({
            url: `${BASE_API_URL}/users?page=${page}&limit=${limit}&search=${search}`,
            method: 'GET',
            success: function(response) {
                $('#showPerPageTotal').text(response.pagination.items_per_page);
                $('#totalData').text(response.pagination.total_items);
                $('#currentPage').text(response.pagination.current_page);
                $('#totalPages').text(response.pagination.total_pages);
                let rowIndex = 1;
                for (let i = 0; i < response.data.length; i++) {
                    const user = response.data[i];
                    let userRow = '';

                    if (user.role_name !== 'Admin') {
                        const verificationStatus = user.user_isverified == 0 ? 'Rejected' : user.user_isverified == 1 ? 'Approved' : 'Unapproved';
                        const badgeType = user.user_isverified == 0 ? 'text-bg-danger' : user.user_isverified == 1 ? 'text-bg-success' : 'text-bg-warning';
                        if (user.role_name === 'Student') {
                            userRow = `
                                <tr>
                                    <td class="px-md-4 py-md-3 text-sm">${rowIndex}</td>
                                    <td class="px-md-4 py-md-3 text-sm">${user.student_name}</td>
                                    <td class="px-md-4 py-md-3 text-sm">${user.role_name}</td>
                                    <td class="px-md-4 py-md-3 text-sm">${user.student_nim}</td>
                                    <td class="px-md-4 py-md-3 text-sm text-center">
                                        <span class="badge ${badgeType}">${verificationStatus}</span>
                                    </td>
                                    <td class="px-md-4 py-md-3 text-sm">
                                        <div class="dropdown">
                                            <button class="btn btn-transparent d-block mx-auto p-0" id="actionsButton" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="fa-solid fa-ellipsis"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="actionsButton">
                                                <li>
                                                    <button type="button" class="dropdown-item d-flex align-items-center gap-2" data-id="${user.user_id}" id="openUserAction">
                                                        <i class="fa-solid fa-edit text-secondary"></i> Open
                                                    </button>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            `;
                            rowIndex++;
                        } else if (user.role_name === 'Lecturer') {
                            userRow = `
                                <tr>
                                    <td class="px-md-4 py-md-3 text-sm">${rowIndex}</td>
                                    <td class="px-md-4 py-md-3 text-sm">${user.lecturer_name}</td>
                                    <td class="px-md-4 py-md-3 text-sm">${user.role_name}</td>
                                    <td class="px-md-4 py-md-3 text-sm">${user.lecturer_nip}</td>
                                    <td class="px-md-4 py-md-3 text-sm text-center">
                                        <span class="badge ${badgeType}">${verificationStatus}</span>
                                    </td>
                                    <td class="px-md-4 py-md-3 text-sm">
                                        <div class="dropdown">
                                            <button class="btn btn-transparent d-block mx-auto p-0" id="actionsButton" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="fa-solid fa-ellipsis"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="actionsButton">
                                                <li>
                                                    <button type="button" class="dropdown-item d-flex align-items-center gap-2" data-id="${user.user_id}" id="openUserAction">
                                                        <i class="fa-solid fa-edit text-secondary"></i> Open
                                                    </button>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            `;
                            rowIndex++;
                        }
                    }
                    userTableBody.append(userRow);
                }

                // Open user action
                document.querySelectorAll('#openUserAction').forEach((button) => {
                    button.addEventListener('click', function () {
                        const userId = $(this).data('id');
                        
                        window.location.href = `user/${userId}`
                    });
                });
            },
            error: function(error) {
                console.log('Error while fetching user data.');
            }
        });
    }

    // Table DataTables
    showPerPagePagination.change(function() {
        const limit = $(this).val();

        fetchAndSetupUsersTable(1, limit);
    });

    prevButtonPagination.click(function() {
        const currentPage = parseInt($('#currentPage').text());

        if (currentPage > 1) {
            fetchAndSetupUsersTable(currentPage - 1, showPerPagePagination.val());
        }
    });

    nextButtonPagination.click(function() {
        const currentPage = parseInt($('#currentPage').text());
        const totalPages = parseInt($('#totalPages').text());

        if (currentPage < totalPages) {
            fetchAndSetupUsersTable(currentPage + 1, showPerPagePagination.val());
        }
    });

    let debounceTimeout;
    searchUserInput.keyup(function () {
        const search = $(this).val();
    
        clearTimeout(debounceTimeout);
    
        debounceTimeout = setTimeout(function () {
            fetchAndSetupUsersTable(1, showPerPagePagination.val(), search);
        }, 300);
    });

    // Run the functions
    fetchAndSetupUsersTable(1, 5);
});