$(document).ready(function() {
    const alertMessageElement = $('#alertMessage');
    const showPerPagePagination = $('#showPerPagePagination');
    const prevButtonPagination = $('#prevButtonPagination');
    const nextButtonPagination = $('#nextButtonPagination');
    const searchStudyProgramInput = $('#searchStudyProgram');

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
                            <td class="px-md-4 py-md-3 text-sm">${studyProgram.department_name}</td>
                            <td class="px-md-4 py-md-3 text-sm">${studyProgram.studyprogram_name}</td>
                            <td class="px-md-4 py-md-3 text-sm">${formatDateToIndonesian(studyProgram.studyprogram_createdat)}</td>
                            <td class="px-md-4 py-md-3 text-sm">${formatDateToIndonesian(studyProgram.studyprogram_updatedat)}</td>
                            <td class="px-md-4 py-md-3 text-sm">
                                <div class="dropdown">
                                    <button class="btn btn-transparent d-block mx-auto p-0" id="actionsButton" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fa-solid fa-ellipsis"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="actionsButton">
                                        <li>
                                            <button type="button" class="dropdown-item d-flex align-items-center gap-2" data-id="${studyProgram.studyprogram_id}" data-name="${studyProgram.studyprogram_name}" data-bs-toggle="modal" data-bs-target="#updateDepartmentModal" id="updateStudyProgramAction">
                                                <i class="fa-solid fa-edit text-secondary"></i> Update
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex align-items-center gap-2" data-id="${studyProgram.studyprogram_id}" data-bs-toggle="modal" data-bs-target="#deleteDepartmentModal" id="deleteStudyProgramAction">
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

                // Delete department button action
                document.querySelectorAll('#deleteStudyProgramAction').forEach((button) => {
                    button.addEventListener('click', function () {
                        const studyProgramId = this.getAttribute('data-id');
            
                        $('#deleteStudyProgramId').val(studyProgramId);
                    });
                });

                // Update department button action
                document.querySelectorAll('#updateStudyProgramAction').forEach((button) => {
                    button.addEventListener('click', function () {
                        const studyProgramId = this.getAttribute('data-id');
                        const studyProgramName = this.getAttribute('data-name');
            
                        $('#updateStudyProgramId').val(studyProgramId);
                        $('#updateStudyProgramName').val(studyProgramName);
                    });
                });
            },
            error: function() {

            }
        });
    };

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

    // Run functions
    fetchAndSetupStudyProgramsTable(1, 5);
});