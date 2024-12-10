<?php

namespace App\Middlewares;

use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class BECheckStudentMiddleware extends Middleware implements MiddlewareInterface {
	public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface {
		if (session_status() === PHP_SESSION_NONE) {
			session_start();
		}

		if (!isset($_SESSION['user']['role']) || $_SESSION['user']['role'] !== 'Student') {
			$responseData = [
				'success' => false,
				'message' => 'Access denied! Student only',
			];
			return $this->respondWithJson($responseData, 403);
		}

		return $handler->handle($request);
	}
}
