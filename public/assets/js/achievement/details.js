$(document).ready(() => {
    // Variables
    const pathname = window.location.pathname;
    const segments = pathname.split('/');
    const achievementId = segments[segments.length - 1];

    let activeTab = 'Information';
    // Tab Elements
    const btnInformationsTab = $('#btnInformationsTab');
    const btnFilesTab = $('#btnFilesTab');
    const informationTabContainer = $('#informationTabContainer');
    const filesTabContainer = $('#filesTabContainer');

    // Setup Tabs
    btnInformationsTab.on('click', function() {
        activeTab = 'Information';
        informationTabContainer.removeClass('d-none');
        filesTabContainer.addClass('d-none');
        btnInformationsTab.addClass('tab-item-active');
        btnFilesTab.removeClass('tab-item-active');
    });

    btnFilesTab.on('click', function() {
        activeTab = 'Files';
        informationTabContainer.addClass('d-none');
        filesTabContainer.removeClass('d-none');
        btnInformationsTab.removeClass('tab-item-active');
        btnFilesTab.addClass('tab-item-active');
    });

    // Setup Details Data
    const fetchAndSetupDetailsData = () => {
        const studentDetailsContainer = $('#studentDetailsContainer');
        const achievementDetailsContainer = $('#achievementDetailsContainer');
        const verificationDetailsContainer = $('#verificationDetailsContainer');
        const fileDetailsContainer = $('#fileDetailsContainer');

        $.ajax({
            url: `${BASE_API_URL}/achievements/${achievementId}`,
            method: 'GET',
            success: function(response) {
                const achievement = response.data;
                // Setup Student Details Container
                const studentDetailsRow = `
                    <div class="col-12 col-md-4 col-lg-3">
                        <div class="d-flex flex-column gap-1">
                            <p class="my-0 text-sm">Name</p>
                            <p class="my-0 text-sm text-secondary">${achievement.student_name}</p>
                        </div>
                    </div>
                    <div class="col-12 col-md-4 col-lg-3">
                        <div class="d-flex flex-column gap-1">
                            <p class="my-0 text-sm">NIM</p>
                            <p class="my-0 text-sm text-secondary">${achievement.student_nim}</p>
                        </div>
                    </div>
                    <div class="col-12 col-md-4 col-lg-3">
                        <div class="d-flex flex-column gap-1">
                            <p class="my-0 text-sm">Email</p>
                            <p class="my-0 text-sm text-secondary">${achievement.student_email}</p>
                        </div>
                    </div>
                    <div class="col-12 col-md-4 col-lg-3">
                        <div class="d-flex flex-column gap-1">
                            <p class="my-0 text-sm">Phone Number</p>
                            <p class="my-0 text-sm text-secondary">${achievement.student_phonenumber}</p>
                        </div>
                    </div>
                `;

                studentDetailsContainer.html(studentDetailsRow);

                // Setup Achievement Details Container
                const categoriesRow = achievement.achievement_category_details.map(category => 
                    `<div class="px-4 py-2 bg-secondary rounded">
                        <p class="my-0 text-xs text-secondary">${category.category_name}</p>
                    </div>`
                ).join('');
                
                const achievementDetailsRow = `
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="d-flex flex-column gap-1">
                            <p class="my-0 text-sm">Title</p>
                            <p class="my-0 text-sm text-secondary">${achievement.achievement_title}</p>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="d-flex flex-column gap-1">
                            <p class="my-0 text-sm">Type</p>
                            <p class="my-0 text-sm text-secondary">${achievement.achievement_type}</p>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="d-flex flex-column gap-1">
                            <p class="my-0 text-sm">Scope</p>
                            <p class="my-0 text-sm text-secondary">${achievement.achievement_scope}</p>
                        </div>
                    </div>
                    <div class="col-12 col-lg-6">
                        <div class="d-flex flex-column gap-1">
                            <p class="my-0 text-sm">Description</p>
                            <p class="my-0 text-sm text-secondary">${achievement.achievement_description}</p>
                        </div>
                    </div>
                    <div class="col-12 col-lg-6">
                        <div class="d-flex flex-column gap-2">
                            <p class="my-0 text-sm">Categories</p>
                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                ${categoriesRow}
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-4 col-lg-3">
                        <div class="d-flex flex-column gap-1">
                            <p class="my-0 text-sm">Location</p>
                            <p class="my-0 text-sm text-secondary">${achievement.achievement_eventlocation}</p>
                        </div>
                    </div>
                    <div class="col-12 col-md-4 col-lg-3">
                        <div class="d-flex flex-column gap-1">
                            <p class="my-0 text-sm">City</p>
                            <p class="my-0 text-sm text-secondary">${achievement.achievement_eventcity}</p>
                        </div>
                    </div>
                    <div class="col-12 col-md-4 col-lg-3">
                        <div class="d-flex flex-column gap-1">
                            <p class="my-0 text-sm">Event Start</p>
                            <p class="my-0 text-sm text-secondary">${formatOnlyDateToIndonesian(achievement.achievement_eventstart)}</p>
                        </div>
                    </div>
                    <div class="col-12 col-md-4 col-lg-3">
                        <div class="d-flex flex-column gap-1">
                            <p class="my-0 text-sm">Event End</p>
                            <p class="my-0 text-sm text-secondary">${formatOnlyDateToIndonesian(achievement.achievement_eventend)}</p>
                        </div>
                    </div>
                `;
                
                achievementDetailsContainer.html(achievementDetailsRow);     
                
                // Setup Verification Details Container
                const approversRow = achievement.achievement_approvers.map(approver => 
                    `<div class="px-3 py-2 bg-secondary rounded d-flex align-items-center gap-3">
                        <div class="rounded-profile-letter" style="background-color: #9CA3AF;">
                            <p class="heading-6 my-0 text-white">${approver.approver_name[0]}</p>
                        </div>
                        <div class="d-flex flex-column">
                            <p class="my-0 text-sm font-medium">${approver.approver_name}</p>
                            <p class="my-0 text-xs text-secondary">NIP: ${approver.approver_nip ? approver.approver_nip : '-'}</p>
                        </div>
                    </div>`
                ).join('');
                const statusBadgeType = achievement.achievement_verification.verification_status == 'Menunggu Persetujuan' ? 'text-bg-warning' : achievement.achievement_verification.verification_status == 'Ditolak' ? 'text-bg-danger' : 'text-bg-success'; 
                const verificationDetailsRow = `
                    <div class="col-12 col-md-6">
                        <div class="d-flex flex-column gap-2" style="width: fit-content;">
                            <p class="my-0 text-sm">Status</p>
                            <span class="badge ${statusBadgeType}">${achievement.achievement_verification.verification_status}</span>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="d-flex flex-column gap-2">
                            <p class="my-0 text-sm">Approvers</p>
                            <div class="d-flex align-items-center gap-2 flex-wrap"> 
                                ${approversRow}
                            </div>
                        </div>
                    </div>
                `;

                verificationDetailsContainer.html(verificationDetailsRow);

                // Setup Files Tab Container
                const fileDetailsRow = achievement.achievement_files.map(file => 
                    `<div class="col-12 col-md-6">
                        <div class="d-flex flex-column gap-2">
                            <div class="d-flex flex-column gap-1">
                                <p class="my-0 text-sm">File Title</p>
                                <p class="my-0 text-sm text-secondary">${file.file_title}</p>
                            </div>
                            <iframe src="${file.file_path}" style="width: 100%; height: 500px;"></iframe>
                        </div>
                    </div>`
                ).join('');

                fileDetailsContainer.html(fileDetailsRow);
            },
            error: function(error) {
                console.log('Error while fetching achievement data!');
            }
        });
    };

    // Run the Functions
    fetchAndSetupDetailsData();
});