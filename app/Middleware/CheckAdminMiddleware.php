<?php

namespace App\Middleware;

use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Response;

class CheckAdminMiddleware implements MiddlewareInterface
{

  public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
  {
    if (session_status() === PHP_SESSION_NONE) {
      session_start();
    }

    if (!isset($_SESSION['user']) || !isset($_SESSION['user']['id'])) {
      $responseData = [
        'success' => false,
        'message' => 'Unauthorized access! Please log in.',
      ];
      return $this->respondWithJson($responseData, 401);
    }

    if (!isset($_SESSION['user']['role']) || $_SESSION['user']['role'] !== '6EC386D9-7313-4659-8C7D-B11148750B7A') {
      $responseData = [
        'success' => false,
        'message' => 'Access denied! Admins only.',
      ];
      return $this->respondWithJson($responseData, 403);
    }

    return $handler->handle($request);
  }

  private function respondWithJson(array $data, int $status): ResponseInterface
  {
    $response = new Response();
    $response->getBody()->write(json_encode($data, JSON_PRETTY_PRINT));
    return $response->withHeader('Content-Type', 'application/json')->withStatus($status);
  }
}
