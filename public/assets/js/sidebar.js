$(document).ready(function() {
    const masterDropdownButton = $('#masterDropdownButton');
    const masterDropdownIcon = $('#masterDropdownIcon');
    const masterDropdown = $('#masterDropdown');

    masterDropdownButton.click(() => {
        const isExpanded = masterDropdown.hasClass('sidebar-dropdown-items-show');

        if (isExpanded) {
            masterDropdown.css('max-height', '0');
        } else {
            const scrollHeight = masterDropdown[0].scrollHeight;
            masterDropdown.css('max-height', `${scrollHeight}px`);
        }

        masterDropdownIcon.toggleClass('fa-chevron-up');
        masterDropdown.toggleClass('sidebar-dropdown-items-show');
    });
});
