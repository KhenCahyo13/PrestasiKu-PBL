<?php

    use App\Controllers\AdminController;
    use App\Controllers\AuthController;
    use App\Controllers\DepartmentController;
    use App\Middlewares\CheckAdminMiddleware;
    use App\Middlewares\CheckAuthMiddleware;

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
        })->add(new CheckAuthMiddleware());

        $api->group('/users', function ($api) {
            $api->get('', AdminController::class . ':getUsers');
            $api->get('/{id}', AdminController::class . ':getUserById');
            $api->patch('/{id}', AdminController::class . ':updateUser');
            $api->delete('/{id}', AdminController::class . ':deleteUser');
        })->add(new CheckAdminMiddleware());
    });
?>
