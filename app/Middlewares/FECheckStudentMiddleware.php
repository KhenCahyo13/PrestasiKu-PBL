<?php

namespace App\Middlewares;

use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Response;

class FECheckStudentMiddleware extends Middleware implements MiddlewareInterface {
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user']['role']) || $_SESSION['user']['role'] !== 'Student') {
            $response = new Response();
            include views('errors/403.php');
            return $response->withStatus(403);
        }

        return $handler->handle($request);
    }
}
