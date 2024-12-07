<?php

use App\Controllers\AchievementController;
use App\Controllers\AdminController;
use App\Controllers\AuthController;
use App\Controllers\DepartmentController;
use App\Middlewares\CheckAdminMiddleware;


use App\Middlewares\CheckAuthMiddleware;
use App\Middlewares\CheckStudentMiddleware;




$app->group('/api', function ($api) {

    $api->group('/auth', function ($api) {
        $api->post('/register', AuthController::class . ':register');
        $api->post('/login', AuthController::class . ':login');
        $api->post('/logout', AuthController::class . ':logout');
    });

    $api->group('/departments', function ($api) {
        $api->get('', DepartmentController::class . ':index');
        $api->get('/{id}', DepartmentController::class . ':show');
        $api->post('', DepartmentController::class . ':store');
        $api->patch('/{id}', DepartmentController::class . ':update');
        $api->delete('/{id}', DepartmentController::class . ':delete');
    })->add(new CheckAuthMiddleware());

    $api->group('/users', function ($api) {
        $api->get('', AdminController::class . ':getUsers');
        $api->get('/{id}', AdminController::class . ':getUserById');
        $api->patch('/{id}', AdminController::class . ':updateUser');
        $api->delete('/{id}', AdminController::class . ':deleteUser');
        $api->patch('/{id}/verify', AdminController::class . ':verifiedRegistration');
    })->add(new CheckAdminMiddleware());

    $api->group('/achievements', function ($api) {
        $api->post('/upload', AchievementController::class . ':createAchievement');
        $api->post('/approval/{id}', AchievementController::class . ':approveAchievement');
        $api->get('/pending/{id}', AchievementController::class . ':getPendingAchievements');
        $api->get('/approved/{id}', AchievementController::class . ':getApprovedAchievements');
        $api->get('/notification/{id}', AchievementController::class . ':getNotifications');
        $api->delete('/delete/{id}', AchievementController::class . ':deleteAchievement');
        $api->get('/grafic-scope', AchievementController::class . ':getAchievementScopePercentage');
    });
});
