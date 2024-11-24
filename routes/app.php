<?php
    use Slim\Factory\AppFactory;

    $app = AppFactory::create();

    $app->setBasePath('/PrestasiKu-PBL/public');

    require_once __DIR__ . '/../routes/api.php';
    require_once __DIR__ . '/../routes/web.php';

    $app->run();
?>