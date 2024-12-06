<?php

use App\Middlewares\CheckAdminMiddleware;
use App\Middlewares\CheckAuthMiddleware;

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
        // Dashboard Page Routes
        $web->get('/dashboard', function($request, $response) {
            include views('dashboard.php');
            return $response;
        })->add(new CheckAuthMiddleware());
        // Master Pages Routes
        $web->group('/master', function($master) {
            $master->get('/department', function($request, $response) {
                include views('master/department.php');
                return $response;
            });
            $master->get('/study-program', function($request, $response) {
                include views('master/study-program.php');
                return $response;
            });
            $master->get('/sp-class', function($request, $response) {
                include views('master/sp-class.php');
                return $response;
            });
        })->add(new CheckAuthMiddleware())
        ->add(new CheckAdminMiddleware());
    });
?>