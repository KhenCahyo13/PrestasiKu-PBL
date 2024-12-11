<?php

use App\Middlewares\FECheckAdminMiddleware;
use App\Middlewares\FECheckAuthMiddleware;
use App\Middlewares\FECheckStudentMiddleware;

$app->group('/web', function ($web) {
    // Auth Group Routes
    $web->group('/auth', function ($auth) {
        $auth->get('/login', function ($request, $response) {
            include views('auth/login.php');
            return $response;
        });
        $auth->get('/register', function ($request, $response) {
            include views('auth/register.php');
            return $response;
        });
        $auth->get('/forgot-password', function ($request, $response) {
            include views('auth/forgot-password.php');
            return $response;
        });
        $auth->get('/reset-password', function ($request, $response) {
            include views('auth/reset-password.php');
            return $response;
        });
    });
    // Dashboard Page Routes
    $web->get('/dashboard', function ($request, $response) {
        include views('dashboard.php');
        return $response;
    })->add(new FECheckAuthMiddleware());
    // Achievement Page Routes
    $web->group('/achievement', function ($achievement) {
        $achievement->get('/add-new', function ($request, $response) {
            include views('achievement/add-new.php');
            return $response;
        })->add(new FECheckStudentMiddleware());
        $achievement->get('', function ($request, $response) {
            include views('achievement/list.php');
            return $response;
        });
        $achievement->get('/{id}', function ($request, $response) {
            include views('achievement/details.php');
            return $response;
        });
    })->add(new FECheckAuthMiddleware());
    // Master Pages Routes
    $web->group('/master', function ($master) {
        $master->get('/department', function ($request, $response) {
            include views('master/department.php');
            return $response;
        });
        $master->get('/study-program', function ($request, $response) {
            include views('master/study-program.php');
            return $response;
        });
        $master->get('/sp-class', function ($request, $response) {
            include views('master/sp-class.php');
            return $response;
        });
        $master->group('/user', function ($user) {
            $user->get('', function ($request, $response) {
                include views('master/user/list.php');
                return $response;
            });
            $user->get('/{id}', function ($request, $response) {
                include views('master/user/verify.php');
                return $response;
            });
        });
    })->add(new FECheckAuthMiddleware())
    ->add(new FECheckAdminMiddleware());
});
