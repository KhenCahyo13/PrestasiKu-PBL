$(document).ready(function() {
	// Init variables
	const alertMessageElement = $('#alertMessage');
	const showPerPagePagination = $('#showPerPagePagination');
	const prevButtonPagination = $('#prevButtonPagination');
	const nextButtonPagination = $('#nextButtonPagination');
	const searchClassInput = $('#searchClass');

	const fetchAndSetupStudyProgramsTable = (page = 1, limit = showPerPagePagination, search = '') => {
		const classTableBody = $('#classTableBody');

		classTableBody.empty();

		$.ajax({
			url: `${BASE_API_URL}/sp-class?page=${page}&limit=${limit}&search=${search}`,
			method: 'GET',
			success: function(response) {

			},
			error: function(response) {
					
			}
		});
	}
});