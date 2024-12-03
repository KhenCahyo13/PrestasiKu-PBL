<?php

use App\Controllers\AchievementController;
use App\Controllers\AdminController;
    use App\Controllers\AuthController;
    use App\Controllers\DepartmentController;
    use App\Middlewares\CheckAdminMiddleware;
    use App\Middlewares\CheckStudentMiddleware;

    $app->group('/api', function($api) {
        $api->group('/auth', function ($api) {
            $api->post('/register', AuthController::class . ':register');
            $api->post('/login', AuthController::class . ':login');
            $api->post('/logout', AuthController::class . ':logout');
        });

        $api->group('/departments', function ($api) {
            $api->get('', DepartmentController::class . ':getDepartments');
            $api->get('/{id}', DepartmentController::class . ':getDepartmentById');
            $api->post('', DepartmentController::class . ':createDepartment');
            $api->patch('/{id}', DepartmentController::class . ':updateDepartment');
            $api->delete('/{id}', DepartmentController::class . ':deleteDepartment');
        })->add(new CheckAdminMiddleware());

        $api->group('/users', function ($api) {
            $api->get('', AdminController::class . ':getUsers');
            $api->get('/{id}', AdminController::class . ':getUserById');
            $api->patch('/{id}', AdminController::class . ':updateUser');
            $api->delete('/{id}', AdminController::class . ':deleteUser');
            $api->patch('/{id}/verify', AdminController::class . ':verifiedRegistration');
        })->add(new CheckAdminMiddleware());

        $api->group('/achievements', function ($api) {
            $api->post('/upload', AchievementController::class . ':createAchievement');
        })->add(new CheckStudentMiddleware());
    });
?>
