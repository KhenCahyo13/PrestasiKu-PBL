<?php

use App\Controllers\AdminController;
use App\Controllers\AuthController;
use App\Controllers\DepartmentController;
use App\Controllers\StudyProgramController;
use App\Middlewares\CheckAdminMiddleware;

use App\Middlewares\CheckAuthMiddleware;

$app->group('/api', function ($api) {
    // Auth Routes
    $api->group('/auth', function ($api) {
        $api->post('/register', AuthController::class . ':register');
        $api->post('/login', AuthController::class . ':login');
        $api->post('/logout', AuthController::class . ':logout');
    });
    // Department Routes
    $api->group('/departments', function ($api) {
        $api->get('', DepartmentController::class . ':index');
        $api->get('/{id}', DepartmentController::class . ':show');
        $api->post('', DepartmentController::class . ':store');
        $api->patch('/{id}', DepartmentController::class . ':update');
        $api->delete('/{id}', DepartmentController::class . ':delete');
    })->add(new CheckAuthMiddleware());
    // Study Program Routes
    $api->group('/study-programs', function ($api) {
        $api->get('', StudyProgramController::class . ':index');
        $api->get('/{id}', StudyProgramController::class . ':show');
        $api->post('', StudyProgramController::class . ':store');
        $api->patch('/{id}', StudyProgramController::class . ':update');
        $api->delete('/{id}', StudyProgramController::class . ':delete');
    })->add(new CheckAuthMiddleware());
    // User Routes
    $api->group('/users', function ($api) {
        $api->get('', AdminController::class . ':getUsers');
        $api->get('/{id}', AdminController::class . ':getUserById');
        $api->patch('/{id}', AdminController::class . ':updateUser');
        $api->delete('/{id}', AdminController::class . ':deleteUser');
        $api->patch('/{id}/verify', AdminController::class . ':verifiedRegistration');
    })->add(new CheckAdminMiddleware());
});
