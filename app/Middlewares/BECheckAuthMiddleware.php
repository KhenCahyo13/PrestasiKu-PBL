<?php

namespace App\Middlewares;

use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class BECheckAuthMiddleware extends Middleware implements MiddlewareInterface {
	public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface {
		if (session_status() === PHP_SESSION_NONE) {
			session_start();
		}

		if (!isset($_SESSION['user'])) {
			$responseData = array(
                'success' => false,
				'message' => 'Unauthenticated! Plase og in first.',
            );

			return $this->respondWithJson($responseData, 401);
		}

		return $handler->handle($request);
	}
}
