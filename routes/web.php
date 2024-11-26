<?php
    $app->group('/web', function($web) {
        // Auth Group Routes
        $web->group('/auth', function($auth) {
            $auth->get('/login', function($request, $response) {
                include views('auth/login.php');
                return $response;
            });
            $auth->get('/register', function($request, $response) {
                include views('auth/register.php');
                return $response;
            });
            $auth->get('/forgot-password', function($request, $response) {
                include views('auth/forgot-password.php');
                return $response;
            });
            $auth->get('/reset-password', function($request, $response) {
                include views('auth/reset-password.php');
                return $response;
            });
        });
        // Pages Routes
        $web->get('/dashboard', function($request, $response) {
            include views('dashboard.php');
            return $response;
        });
    });
?>