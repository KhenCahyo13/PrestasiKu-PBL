<?php
    use Slim\Factory\AppFactory;
    use App\Controllers\AuthController;
    use App\Middleware\CheckAuthMiddleware;

    $api = AppFactory::create();

    $api->setBasePath('/PrestasiKu-PBL/public/api');

    $api->group('/auth', function ($api) {
        $api->post('/register', AuthController::class . ':register');
    })->add(CheckAuthMiddleware::class);

    $api->run();
?>