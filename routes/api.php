<?php

use App\Controllers\AchievementCategoryController;
use App\Controllers\AuthController;
use App\Controllers\DepartmentController;
use App\Controllers\RoleController;
use App\Controllers\SPClassController;
use App\Controllers\StudyProgramController;
use App\Controllers\AchievementController;
use App\Controllers\UserController;
use App\Middlewares\BECheckAdminMiddleware;
use App\Middlewares\BECheckAuthMiddleware;
use App\Middlewares\BECheckStudentMiddleware;

$app->group('/api', function ($api) {
    // Auth Routes
    $api->group('/auth', function ($api) {
        $api->post('/register', AuthController::class . ':register');
        $api->post('/login', AuthController::class . ':login');
        $api->post('/logout', AuthController::class . ':logout');
    });
    // Achievement Routes
    $api->group('/achievements', function ($api) {
        $api->get('/grafic-scope', AchievementController::class . ':getAchievementScopePercentage');
        $api->get('', AchievementController::class . ':index');
        $api->get('/{id}', AchievementController::class . ':show');
        $api->get('/{id}/approver-list', AchievementController::class . ':getApproverList');
        $api->post('', AchievementController::class . ':store')->add(new BECheckStudentMiddleware());
        $api->post('/approval/{id}', AchievementController::class . ':approveAchievement');
        $api->get('/pending/{id}', AchievementController::class . ':getPendingAchievements');
        $api->get('/approved/{id}', AchievementController::class . ':getApprovedAchievements');
        $api->get('/notification/{id}', AchievementController::class . ':getNotifications');
        $api->delete('/delete/{id}', AchievementController::class . ':deleteAchievement');
    })->add(new BECheckAuthMiddleware());
    // Achievement Categories Routes
    $api->group('/achievement-categories', function ($api) {
        $api->get('', AchievementCategoryController::class . ':index');
    });
    // Roles Routes
    $api->group('/roles', function ($api) {
        $api->get('', RoleController::class . ':index');
    });
    // Department Routes
    $api->get('/departments', DepartmentController::class . ':index');
    $api->group('/departments', function ($api) {
        $api->get('/{id}', DepartmentController::class . ':show');
        $api->post('', DepartmentController::class . ':store')->add(new BECheckAdminMiddleware());
        $api->patch('/{id}', DepartmentController::class . ':update')->add(new BECheckAdminMiddleware());
        $api->delete('/{id}', DepartmentController::class . ':delete')->add(new BECheckAdminMiddleware());
    })->add(new BECheckAuthMiddleware());
    // Study Program Routes
    $api->group('/study-programs', function ($api) {
        $api->get('', StudyProgramController::class . ':index');
        $api->get('/{id}', StudyProgramController::class . ':show');
        $api->post('', StudyProgramController::class . ':store')->add(new BECheckAdminMiddleware());
        $api->patch('/{id}', StudyProgramController::class . ':update')->add(new BECheckAdminMiddleware());
        $api->delete('/{id}', StudyProgramController::class . ':delete')->add(new BECheckAdminMiddleware());
    })->add(new BECheckAuthMiddleware());
    // SP Class Routes
    $api->get('/sp-classes', SPClassController::class . ':index');
    $api->group('/sp-classes', function ($api) {
        $api->get('/{id}', SPClassController::class . ':show');
        $api->post('', SPClassController::class . ':store')->add(new BECheckAdminMiddleware());
        $api->patch('/{id}', SPClassController::class . ':update')->add(new BECheckAdminMiddleware());
        $api->delete('/{id}', SPClassController::class . ':delete')->add(new BECheckAdminMiddleware());
    })->add(new BECheckAuthMiddleware());
    // User Routes
    $api->group('/users', function ($api) {
        $api->get('/{id}', UserController::class . ':show');
        $api->get('', UserController::class . ':index');
    })->add(new BECheckAuthMiddleware());
    $api->group('/users', function ($api) {
        $api->patch('/{id}/verify', UserController::class . ':verifyUserRegistration');
    })->add(new BECheckAuthMiddleware())
    ->add(new BECheckAdminMiddleware());
});
