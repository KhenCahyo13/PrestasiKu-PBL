<?php
    use Slim\Factory\AppFactory;
    use App\Controllers\AuthController;
    use App\Controllers\DepartmentController;
    use App\Middleware\CheckAdminMiddleware;


    $api = AppFactory::create();

    $api->setBasePath('/PrestasiKu-PBL/public/api');

    $api->get('/test-uuid', AuthController::class . ':testUuid');
    $api->post('/test', AuthController::class . ':testResponse');

    $api->group('/auth', function ($api) {
        $api->post('/register', AuthController::class . ':register');
        $api->post('/login', AuthController::class . ':login');
        $api->post('/logout', AuthController::class . ':logout');
    });

    


    $api->run();
?>