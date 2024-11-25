<?php
    $app->group('/web', function($web) {
        // Auth Group Routes
        $web->group('/auth', function($auth) {
            $auth->get('/login', function($request, $response) {
                include views('pages/auth/login.php');
                return $response;
            });
            $auth->get('/register', function($request, $response) {
                include views('pages/auth/register.php');
                return $response;
            });
            $auth->get('/forgot-password', function($request, $response) {
                include views('pages/auth/forgot-password.php');
                return $response;
            });
            $auth->get('/reset-password', function($request, $response) {
                include views('pages/auth/reset-password.php');
                return $response;
            });
        });
    });
?>