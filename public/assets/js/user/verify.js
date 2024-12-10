$(document).ready(function () {
    // Get userId params
    const pathname = window.location.pathname;
    const segments = pathname.split('/');
    const userId = segments[segments.length - 1];

    // HTML Elements
    const accountDetailsElement = $('#accountDetailsContainer');
    const userDetailsElement = $('#userDetailsContainer');
    const actionsElement = $('#actionsContainer');
    const alertMessageElement = $('#alertMessageElement');
    const approveModalElement = $('#approveModal');
    const rejectModalElement = $('#rejectModal');

    // Setup User Details Page
    $.ajax({
        url: `${BASE_API_URL}/users/${userId}`,
        method: 'GET',
        success: function (response) {
            const user = response.data;
            let userDetails = '';

            // Mapping account details
            const verificationStatus = user.user_isverified == 0 ? 'Rejected' : user.user_isverified == 1 ? 'Approved' : 'Unapproved';
            const textType = user.user_isverified == 0 ? 'text-danger' : user.user_isverified == 1 ? 'text-success' : 'text-warning';
            const accountDetails = `
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="d-flex flex-column gap-1">
                        <p class="my-0 text-sm font-semibold">Username</p>
                        <p class="my-0 text-sm">${user.user_username}</p>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="d-flex flex-column gap-1">
                        <p class="my-0 text-sm font-semibold">User Role</p>
                        <p class="my-0 text-sm">${user.role_name}</p>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="d-flex flex-column gap-1">
                        <p class="my-0 text-sm font-semibold">Verification Status</p>
                        <p class="my-0 text-sm ${textType}">${verificationStatus}</p>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="d-flex flex-column gap-1">
                        <p class="my-0 text-sm font-semibold">Register at</p>
                        <p class="my-0 text-sm">${formatDateToIndonesian(user.user_createdat)}</p>
                    </div>
                </div>
            `;

            if (user.user_isverified != 1) {
                const actions = `
                    <div class="px-3 py-2">
                        <div class="d-flex align-items-center gap-2 justify-content-end">
                            <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#rejectModal">Reject</button>
                            <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#approveModal">Approve</button>
                        </div>
                    </div>
                `;

                actionsElement.append(actions);
            }

            if (user.role_name === 'Student') {
                userDetails = `
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="d-flex flex-column gap-1">
                            <p class="my-0 text-sm font-semibold">Fullname</p>
                            <p class="my-0 text-sm">${user.detail_name}</p>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="d-flex flex-column gap-1">
                            <p class="my-0 text-sm font-semibold">NIM</p>
                            <p class="my-0 text-sm">${user.detail_nim}</p>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="d-flex flex-column gap-1">
                            <p class="my-0 text-sm font-semibold">Class</p>
                            <p class="my-0 text-sm">${user.spclass_name}</p>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="d-flex flex-column gap-1">
                            <p class="my-0 text-sm font-semibold">Date of Birth</p>
                            <p class="my-0 text-sm">${user.detail_dateofbirth}</p>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="d-flex flex-column gap-1">
                            <p class="my-0 text-sm font-semibold">Phone Number</p>
                            <p class="my-0 text-sm">${user.detail_phonenumber}</p>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="d-flex flex-column gap-1">
                            <p class="my-0 text-sm font-semibold">Email</p>
                            <p class="my-0 text-sm">${user.detail_email}</p>
                        </div>
                    </div>
                `;
            } else if (user.role_name === 'Lecturer') {
                userDetails = `
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="d-flex flex-column gap-1">
                            <p class="my-0 text-sm font-semibold">Fullname</p>
                            <p class="my-0 text-sm">${user.detail_name}</p>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="d-flex flex-column gap-1">
                            <p class="my-0 text-sm font-semibold">NIP</p>
                            <p class="my-0 text-sm">${user.detail_nip}</p>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="d-flex flex-column gap-1">
                            <p class="my-0 text-sm font-semibold">Department</p>
                            <p class="my-0 text-sm">${user.department_name}</p>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="d-flex flex-column gap-1">
                            <p class="my-0 text-sm font-semibold">Phone Number</p>
                            <p class="my-0 text-sm">${user.detail_phonenumber}</p>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="d-flex flex-column gap-1">
                            <p class="my-0 text-sm font-semibold">Email</p>
                            <p class="my-0 text-sm">${user.detail_email}</p>
                        </div>
                    </div>
                `;
            }

            accountDetailsElement.append(accountDetails)
            userDetailsElement.append(userDetails);
        },
        error: function (error) {
            console.log('Error while getting user data by id.')
        }
    });

    // Verifications Actions
    const approveButton = $('#approveButton');
    const rejectButton = $('#rejectButton');

    const verifyUser = (verificationAction) => {
        $.ajax({
            url: `${BASE_API_URL}/users/${userId}/verify?action=${verificationAction}`,
            method: 'PATCH',
            success: function (response) {
                let alertMessage = '';
                window.location.reload();

                if (response.success) {
                    alertMessageElement.html = `
                        <div class="my-0 alert alert-success alert-dismissible fade show" role="alert">
                            <p class="my-0 text-sm">
                                <strong>Success!</strong> ${response.message}
                            </p>
                            <button type="button" class="btn btn-close btn-sm" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    `;
                } else {
                    alertMessageElement.html = `
                        <div class="my-0 alert alert-danger alert-dismissible fade show" role="alert">
                            <p class="my-0 text-sm">
                                <strong>Failed!</strong> ${response.message}
                            </p>
                            <button type="button" class="btn btn-close btn-sm" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    `;
                }
            },
            error: function (error) {
                console.log('Error while verify user.');
            }
        });
    };

    approveButton.on('click', function() {
        verifyUser('approve');
    });

    rejectButton.on('click', function() {
        verifyUser('reject');
    });
});