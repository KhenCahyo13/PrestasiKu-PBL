$(document).ready(function() {
    // Navbar Interaction
    const navbarButton = $('#navbarButton');
    const navbarButtonIcon = $('#navbarButtonIcon');
    const sidebarElement = $('#sidebarElement');

    navbarButton.click(function() {
        sidebarElement.toggleClass('d-none');
        navbarButtonIcon.toggleClass('fa-xmark')
    });

    // Logout
    const logoutButton = $('#logoutButton');

    logoutButton.click(function() {
        $.ajax({
            url: `${BASE_API_URL}/auth/logout`,
            method: 'POST',
            success: function(response) {
                if (response.success) {
                    window.location.href = redirectSuccessLogoutUrl;
                } else {
                    alert('Failed to logout!')
                }
            },
            error: function() {
                alert('Failed to logout!')
            }
        })
    });
});