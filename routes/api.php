<?php
    use Slim\Factory\AppFactory;
    use App\Controllers\AuthController;

    $api = AppFactory::create();

    $api->setBasePath('/PrestasiKu-PBL/public/api');

    $api->group('/auth', function ($api) {
        $api->post('/register', AuthController::class . ':register');
    });

    $api->run();
?>