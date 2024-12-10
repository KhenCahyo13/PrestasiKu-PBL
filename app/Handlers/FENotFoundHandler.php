<?php

namespace App\Handlers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpNotFoundException;
use Slim\Interfaces\ErrorHandlerInterface;
use Slim\Psr7\Response;
use Throwable;

class FENotFoundHandler implements ErrorHandlerInterface {
    public function __invoke(ServerRequestInterface $request, Throwable $exception, bool $displayErrorDetails, bool $logErrors, bool $logErrorDetails): ResponseInterface {
        if ($exception instanceof HttpNotFoundException) {
            $response = new Response();
            include views('errors/404.php');
            return $response->withStatus(404);
        }

        throw $exception;
    }
}