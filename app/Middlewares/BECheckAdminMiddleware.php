<?php

namespace App\Middlewares;

use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class BECheckAdminMiddleware extends Middleware implements MiddlewareInterface {
	public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface {
		if (session_status() === PHP_SESSION_NONE) {
			session_start();
		}

		if (!isset($_SESSION['user']['role']) || $_SESSION['user']['role'] !== 'Admin') {
			$responseData = [
				'success' => false,
				'message' => 'Access denied! Admin only.',
			];
			return $this->respondWithJson($responseData, 403);
		}

		return $handler->handle($request);
	}
}
