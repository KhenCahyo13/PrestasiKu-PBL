$(document).ready(function() {
	// Init variables
	const alertMessageElement = $('#alertMessage');
	const showPerPagePagination = $('#showPerPagePagination');
	const prevButtonPagination = $('#prevButtonPagination');
	const nextButtonPagination = $('#nextButtonPagination');
	const searchSpClassInput = $('#searchSpClass');

	const fetchAndSetupClassTable = (page = 1, limit = showPerPagePagination, search = '') => {
		const classTableBody = $('#classTableBody');

		classTableBody.empty();

		$.ajax({
			url: `${BASE_API_URL}/sp-classes?page=${page}&limit=${limit}&search=${search}`,
			method: 'GET',
			success: function(response) {
				$('#showPerPageTotal').text(response.pagination.items_per_page);
                $('#totalData').text(response.pagination.total_items);
                $('#currentPage').text(response.pagination.current_page);
                $('#totalPages').text(response.pagination.total_pages);
				for (let i = 0; i < response.data.length; i++) {
                    const spClass = response.data[i];
					const classRow = `
						<tr>
                            <td class="px-md-4 py-md-3 text-sm">${i + 1}</td>
                            <td class="px-md-4 py-md-3 text-sm">${spClass.spclass_name}</td>
                            <td class="px-md-4 py-md-3 text-sm">${spClass.studyprogram_name}</td>
                            <td class="px-md-4 py-md-3 text-sm">${formatDateToIndonesian(spClass.spclass_createdat)}</td>
                            <td class="px-md-4 py-md-3 text-sm">${formatDateToIndonesian(spClass.spclass_updatedat)}</td>
                            <td class="px-md-4 py-md-3 text-sm">
                                <div class="dropdown">
                                    <button class="btn btn-transparent d-block mx-auto p-0" id="actionsButton" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fa-solid fa-ellipsis"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="actionsButton">
                                        <li>
                                            <button type="button" class="dropdown-item d-flex align-items-center gap-2" data-id="${spClass.spclass_id}" data-name="${spClass.spclass_name}" data-studyprogram="${spClass.studyprogram_name}" data-bs-toggle="modal" data-bs-target="#updateSpClassModal" id="updateSpClassAction">
                                                <i class="fa-solid fa-edit text-secondary"></i> Update
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item d-flex align-items-center gap-2" data-id="${spClass.spclass_id}" data-bs-toggle="modal" data-bs-target="#deleteSpClassModal" id="deleteSpClassAction">
                                                <i class="fa-solid fa-trash text-secondary"></i> Delete
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
					`;
					classTableBody.append(classRow);
				}

				// Delete spclass button action
				document.querySelectorAll('#deleteSpClassAction').forEach((button) => {
					button.addEventListener('click', function () {
						const spClassId = this.getAttribute('data-id');
			
						$('#deleteSpClassId').val(spClassId);
					});
				});

				// Update spclass button action
				document.querySelectorAll('#updateSpClassAction').forEach((button) => {
					button.addEventListener('click', function () {
						const spClassId = this.getAttribute('data-id');
						const spClassName = this.getAttribute('data-name');
						const studyProgramId = this.getAttribute('data-studyprogram');
			
						$('#updateSpClassId').val(spClassId);
						$('#updateSpClassName').val(spClassName);
						$('#updateStudyProgramId').val(studyProgramId);
					});
				});
			},
			error: function(response) {
				console.log('Error while fetching sp class data!')
			}
		});
	}

	// Table DataTables
	showPerPagePagination.change(function() {
		const limit = $(this).val();

		fetchAndSetupClassTable(1, limit);
	});

	prevButtonPagination.click(function() {
		const currentPage = parseInt($('#currentPage').text());

		if (currentPage > 1) {
			fetchAndSetupClassTable(currentPage - 1, showPerPagePagination.val());
		}
	});

	nextButtonPagination.click(function() {
		const currentPage = parseInt($('#currentPage').text());
		const totalPages = parseInt($('#totalPages').text());

		if (currentPage < totalPages) {
			fetchAndSetupClassTable(currentPage + 1, showPerPagePagination.val());
		}
	});

	let debounceTimeout;
	searchSpClassInput.keyup(function () {
		const search = $(this).val();
	
		clearTimeout(debounceTimeout);
	
		debounceTimeout = setTimeout(function () {
			fetchAndSetupClassTable(1, showPerPagePagination.val(), search);
		}, 300);
	});

	// Run functions
	fetchAndSetupClassTable(1, 5);
});