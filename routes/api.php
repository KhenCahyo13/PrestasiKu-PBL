<?php

use App\Controllers\AuthController;
use App\Controllers\DepartmentController;
use App\Controllers\RoleController;
use App\Controllers\SPClassController;
use App\Controllers\StudyProgramController;
use App\Controllers\UserController;
use App\Middlewares\CheckAdminMiddleware;

use App\Middlewares\CheckAuthMiddleware;

$app->group('/api', function ($api) {
    // Auth Routes
    $api->group('/auth', function ($api) {
        $api->post('/register', AuthController::class . ':register');
        $api->post('/login', AuthController::class . ':login');
        $api->post('/logout', AuthController::class . ':logout');
    });
    // Roles Routes
    $api->group('/roles', function ($api) {
        $api->get('', RoleController::class . ':index');
    });
    // Department Routes
    $api->get('/departments', DepartmentController::class . ':index');
    $api->group('/departments', function ($api) {
        $api->get('/{id}', DepartmentController::class . ':show');
        $api->post('', DepartmentController::class . ':store')->add(new CheckAdminMiddleware());
        $api->patch('/{id}', DepartmentController::class . ':update')->add(new CheckAdminMiddleware());
        $api->delete('/{id}', DepartmentController::class . ':delete')->add(new CheckAdminMiddleware());
    })->add(new CheckAuthMiddleware());
    // Study Program Routes
    $api->group('/study-programs', function ($api) {
        $api->get('', StudyProgramController::class . ':index');
        $api->get('/{id}', StudyProgramController::class . ':show');
        $api->post('', StudyProgramController::class . ':store')->add(new CheckAdminMiddleware());
        $api->patch('/{id}', StudyProgramController::class . ':update')->add(new CheckAdminMiddleware());
        $api->delete('/{id}', StudyProgramController::class . ':delete')->add(new CheckAdminMiddleware());
    })->add(new CheckAuthMiddleware());
    // SP Class Routes
    $api->get('/sp-classes', SPClassController::class . ':index');
    $api->group('/sp-classes', function ($api) {
        $api->get('/{id}', SPClassController::class . ':show');
        $api->post('', SPClassController::class . ':store')->add(new CheckAdminMiddleware());
        $api->patch('/{id}', SPClassController::class . ':update')->add(new CheckAdminMiddleware());
        $api->delete('/{id}', SPClassController::class . ':delete')->add(new CheckAdminMiddleware());
    })->add(new CheckAuthMiddleware());
    // User Routes
    $api->get('/users/{id}', UserController::class . ':show')->add(new CheckAuthMiddleware());
    $api->group('/users', function ($api) {
        $api->get('', UserController::class . ':index');
        $api->patch('/{id}/verify', UserController::class . ':verifyUserRegistration');
    })->add(new CheckAdminMiddleware());
});
