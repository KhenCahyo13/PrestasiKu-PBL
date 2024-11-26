$(document).ready(function() {
    const navbarButton = $('#navbarButton');
    const navbarButtonIcon = $('#navbarButtonIcon');
    const sidebarElement = $('#sidebarElement');

    navbarButton.click(function() {
        sidebarElement.toggleClass('d-none');
        navbarButtonIcon.toggleClass('fa-xmark')
    });
});