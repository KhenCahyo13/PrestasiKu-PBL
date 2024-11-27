$(document).ready(function() {
    const loginForm = $('#loginForm');
    const username = $('#username');
    const password = $('#password');
    const alertMessageElement = $('#alertMessage');

    loginForm.submit(function(event) {
        event.preventDefault();

        $('#usernameError').text('');
        $('#passwordError').text('');

        let isValid = true;

        if (username.val() === '') {
            $('#usernameError').text('Username is required');
            isValid = false;
        }

        if (password.val() === '') {
            $('#passwordError').text('Password is required');
            isValid = false;
        }

        if (!isValid) {
            return false;
        }

        const data = {
            user_username: username.val(),
            user_password: password.val()
        };

        $.ajax({
            url: `${BASE_API_URL}/auth/login`,
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(data),
            success: function(response) {
                if (response.success) {
                    window.location.href = redirectSuccessLoginUrl;
                } else {
                    alertMessageElement.html(`
                        <div class="my-0 alert alert-danger alert-dismissible fade show" role="alert">
                            <p class="my-0 text-sm">
                                <strong>Failed!</strong> ${response.message}
                            </p>
                            <button type="button" class="btn btn-close btn-sm" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    `);
                }
            },
            error: function() {
                alertMessageElement.html(`
                    <div class="my-0 alert alert-danger alert-dismissible fade show" role="alert">
                        <p class="my-0 text-sm">
                            <strong>Failed!</strong> ${response.message}
                        </p>
                        <button type="button" class="btn btn-close btn-sm" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                `);
            }
        });
    });
});