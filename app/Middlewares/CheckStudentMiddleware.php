<?php

namespace App\Middlewares;

use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Response;

class CheckStudentMiddleware extends Middleware implements MiddlewareInterface
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

    if (!isset($_SESSION['user']['role']) || $_SESSION['user']['role'] !== '39DC23CD-CB63-4073-A15A-2D8A878A3F58') {
      $responseData = [
        'success' => false,
        'message' => 'Access denied! Student only.',
      ];
      return $this->respondWithJson($responseData, 403);
    }

    return $handler->handle($request);
  }
  // response
  private function respondWithJson(array $data, int $status): ResponseInterface
  {
    $response = new Response();
    $response->getBody()->write(json_encode($data, JSON_PRETTY_PRINT));
    return $response->withHeader('Content-Type', 'application/json')->withStatus($status);
  }
}
