<?php

use App\Handlers\FENotFoundHandler;
use Slim\Exception\HttpNotFoundException;
use Slim\Factory\AppFactory;

$app = AppFactory::create();

$app->setBasePath('/PrestasiKu-PBL');


$errorMiddleware = $app->addErrorMiddleware(true, true, true);
$errorMiddleware->setErrorHandler(HttpNotFoundException::class, new FENotFoundHandler());


require_once __DIR__ . '/../routes/resources.php';
require_once __DIR__ . '/../routes/api.php';
require_once __DIR__ . '/../routes/web.php';

$app->run();
