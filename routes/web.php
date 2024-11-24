<?php
    $app->group('/web', function($web) {
        // Auth Group Routes
        $web->group('/auth', function($auth) {
            $auth->get('/login', function($request, $response) {
                include views('pages/auth/login.php');
                return $response;
            });
        });
    });
?>