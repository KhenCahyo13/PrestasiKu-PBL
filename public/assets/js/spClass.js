$(document).ready(function() {
	// Init variables
	const alertMessageElement = $('#alertMessage');
	const showPerPagePagination = $('#showPerPagePagination');
	const prevButtonPagination = $('#prevButtonPagination');
	const nextButtonPagination = $('#nextButtonPagination');
	const searchSpClassInput = $('#searchSpClass');
	const createStudyProgramIdSelectInput = $('#createStudyProgramId');
	const updateStudyProgramIdSelectInput = $('#updateStudyProgramId');

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
                                            <button type="button" class="dropdown-item d-flex align-items-center gap-2" data-id="${spClass.spclass_id}" data-name="${spClass.spclass_name}" data-studyprogram="${spClass.studyprogram_id}" data-bs-toggle="modal" data-bs-target="#updateSpClassModal" id="updateSpClassAction">
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

	// Create a new sp class
	const createSpClass = () => {
        const createSpClassForm = $('#createSpClassForm');
        const createSpClassModal = $('#createSpClassModal');
        const spClassName = $('#createSpClassName');
        const studyProgramId = $('#createStudyProgramId');
        
        createSpClassForm.submit(function(event) {
            event.preventDefault();

            $('#createSpClassNameError').text('');
            $('#createStudyProgramIdError').text('');

            let isValid = true;

            if (spClassName.val() === '') {
                $('#createSpClassNameError').text('Class name is required');
                isValid = false;
            }

            if (studyProgramId.val() === '') {
                $('#createStudyProgramIdError').text('Study program name is required');
                isValid = false;
            }

            if (!isValid) {
                return false;
            }

            const data = {
                spclass_name: spClassName.val(),
                studyprogram_id: studyProgramId.val()
            };

            $.ajax({
                url: `${BASE_API_URL}/sp-classes`,
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify(data),
                success: function(response) {
                    fetchAndSetupClassTable(1, 5);
                    createSpClassModal.modal('hide');
                    createSpClassForm[0].reset();
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
                                <strong>Failed!</strong> Failed when create class.
                            </p>
                            <button type="button" class="btn btn-close btn-sm" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    `);
                }
            });
        });
    }

	// Update a sp class
	// Update a study program
    const updateSpClassButton = $('#updateSpClassButton');
    updateSpClassButton.click(function() {
        const spClassId = $('#updateSpClassId').val();
        const studyProgramId = $('#updateStudyProgramId').val();
        const spClassName = $('#updateSpClassName').val();
        const updateSpClassModal = $('#updateSpClassModal');

        const data = {
            spclass_name: spClassName,
            studyprogram_id: studyProgramId
        };

        $.ajax({
            url: `${BASE_API_URL}/sp-classes/${spClassId}`,
            method: 'PATCH',
            contentType: 'application/json',
            data: JSON.stringify(data),
            success: function(response)  {
                updateSpClassModal.modal('hide');
                fetchAndSetupClassTable(1, 5);
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
                updateSpClassModal.modal('hide');
                alertMessageElement.html(`
                    <div class="my-2 alert alert-danger alert-dismissible fade show" role="alert">
                        <p class="my-0 text-sm">
                            <strong>Failed!</strong> Failed when update class.
                        </p>
                        <button type="button" class="btn btn-close btn-sm" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                `);
            }
        });
    });
	
	// Delete a sp class
	const deleteSpClassButton = $('#deleteSpClassButton');
	deleteSpClassButton.click(function() {
		const spClassId = $('#deleteSpClassId').val();
		const deleteSpClassModal = $('#deleteSpClassModal');

		$.ajax({
			url: `${BASE_API_URL}/sp-classes/${spClassId}`,
			method: 'DELETE',
			success: function(response)  {
				deleteSpClassModal.modal('hide');
				fetchAndSetupClassTable(1, 5);
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
				deleteSpClassModal.modal('hide');
				alertMessageElement.html(`
					<div class="my-2 alert alert-danger alert-dismissible fade show" role="alert">
						<p class="my-0 text-sm">
							<strong>Failed!</strong> Failed when delete class.
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

	// Setup sp class input
	$.ajax({
		url: `${BASE_API_URL}/study-programs?page=1&limit=100&search=`,
		method: 'GET',
		success: function(response) {
			for (let i = 0; i < response.data.length; i++) {
				const studyProgram = response.data[i];
				const studyProgramIdOptionInput = `
					<option value="${studyProgram.studyprogram_id}">${studyProgram.studyprogram_name}</option>
				`;
				createStudyProgramIdSelectInput.append(studyProgramIdOptionInput);
				updateStudyProgramIdSelectInput.append(studyProgramIdOptionInput);
			}
		},
		error: function(response) {
			console.log('Error while fetching study programs data!');
		}
	});

	// Run functions
	fetchAndSetupClassTable(1, 5);
	createSpClass();
});