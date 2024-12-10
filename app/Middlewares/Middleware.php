<?php
namespace App\Middlewares;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Psr7\Response;

abstract class Middleware {
    /**
     *
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    abstract public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface;

    protected function respondWithJson(array $data, int $status): ResponseInterface {
		$response = new Response();
		$response->getBody()->write(json_encode($data, JSON_PRETTY_PRINT));
		return $response->withHeader('Content-Type', 'application/json')->withStatus($status);
	}
}
