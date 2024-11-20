<?php

namespace App\Middleware;

use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Server\MiddlewareInterface;
use Slim\Psr7\Response as Psr7Response;

    class CheckAuthMiddleware implements MiddlewareInterface {
        private string $jwtSecretKey = 'secret-key';

        public function process(Request $request, RequestHandlerInterface $handler): Response
        {
            $authorizationHeader = $request->getHeaderLine('Authorization');

            if (empty($authorizationHeader) || strpos($authorizationHeader, 'Bearer ') !== 0) {
                $response = new Psr7Response();
                $responseData = [
                    'success' => false,
                    'message' => 'Token not provided!',
                    'data' => null,
                ];

                $response->getBody()->write(json_encode($responseData));

                return $response
                    ->withHeader('Content-Type', 'application/json')
                    ->withStatus(401);
            }

            $token = substr($authorizationHeader, 7);

            try {
                $decoded = JWT::decode($token, new Key($this->jwtSecretKey, 'HS256'));

                $request = $request->withAttribute('user', $decoded);

                return $handler->handle($request);
            } catch (Exception $e) {
                $response = new Psr7Response();
                $responseData = [
                    'success' => false,
                    'message' => 'Invalid token!',
                    'data' => null,
                ];

                $response->getBody()->write(json_encode($responseData));

                return $response
                    ->withHeader('Content-Type', 'application/json')
                    ->withStatus(401);
            }
        }
    }
?>