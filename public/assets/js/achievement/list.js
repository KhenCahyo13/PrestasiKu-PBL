$(document).ready(() => {
    // Table Elements
    const showPerPagePagination = $('#showPerPagePagination');
    const prevButtonPagination = $('#prevButtonPagination');
    const nextButtonPagination = $('#nextButtonPagination');
    const searchAchievementInput = $('#searchAchievement');

    // Setup Achievements Table Body
    const fetchAndSetupDepartmentsTable = (page = 1, limit = showPerPagePagination, search = '') => {
        const achievementsTableBody = $('#achievementsTableBody');

        achievementsTableBody.empty();

        $.ajax({
            url: `${BASE_API_URL}/achievements?page=${page}&limit=${limit}&search=${search}`,
            method: 'GET',
            success: function(response) {
                $('#showPerPageTotal').text(response.pagination.items_per_page);
                $('#totalData').text(response.pagination.total_items);
                $('#currentPage').text(response.pagination.current_page);
                $('#totalPages').text(response.pagination.total_pages);

                if (response.data.length === 0) {
                    achievementsTableBody.append(`
                        <tr>
                            <td colspan="7" class="text-center py-3 text-sm text-secondary">Achievements data is still empty.</td>
                        </tr>
                    `);
                    return;
                } else {
                    for (let i = 0; i < response.data.length; i++) {
                        const achievement = response.data[i];
                        const statusBadgeType = achievement.achievement_verification.verification_status == 'Menunggu Persetujuan' ? 'text-bg-warning' : achievement.achievement_verification.verification_status == 'Ditolak' ? 'text-bg-danger' : 'text-bg-success'; 
                        const achievementRow = `
                            <tr>
                                <td class="px-md-4 py-md-3 text-sm">${i + 1}</td>
                                <td class="px-md-4 py-md-3 text-sm">${achievement.achievement_title}</td>
                                <td class="px-md-4 py-md-3 text-sm">${achievement.achievement_type}</td>
                                <td class="px-md-4 py-md-3 text-sm">${achievement.achievement_scope}</td>
                                <td class="px-md-4 py-md-3 text-sm text-center">
                                    <span class="badge ${statusBadgeType}">${achievement.achievement_verification.verification_status}</span>
                                </td>
                                <td class="px-md-4 py-md-3 text-sm">${formatDateToIndonesian(achievement.achievement_createdat)}</td>
                                <td class="px-md-4 py-md-3 text-sm">
                                    <div class="dropdown">
                                        <button class="btn btn-transparent d-block mx-auto p-0" id="actionsButton" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fa-solid fa-ellipsis"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="actionsButton">
                                            <li>
                                                <a href="#" class="dropdown-item d-flex align-items-center gap-2">
                                                    <i class="fa-solid fa-edit text-secondary"></i> Open
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#" class="dropdown-item d-flex align-items-center gap-2">
                                                    <i class="fa-solid fa-clock-rotate-left text-secondary"></i> History
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#" class="dropdown-item d-flex align-items-center gap-2">
                                                    <i class="fa-solid fa-users text-secondary"></i> Approver List
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        `;
    
                        achievementsTableBody.append(achievementRow);
                    }
                }
            },
            error: function(error) {
                console.log('Error while fetching achievement data!');
            }
        });
    };

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
    searchAchievementInput.keyup(function () {
        const search = $(this).val();
    
        clearTimeout(debounceTimeout);
    
        debounceTimeout = setTimeout(function () {
            fetchAndSetupDepartmentsTable(1, showPerPagePagination.val(), search);
        }, 300);
    });

    // Run the Functions
    fetchAndSetupDepartmentsTable(1, 5);
});