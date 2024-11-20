<?php
    namespace App\Middleware;

    use Psr\Http\Server\RequestHandlerInterface;
    use Psr\Http\Message\RequestInterface as Request;
    use Psr\Http\Message\ResponseInterface as Response;

    abstract class Middleware {
        /**
         * Method untuk menangani request di middleware.
         *
         * @param Request $request
         * @param Response $response
         * @param callable $next Fungsi untuk melanjutkan ke middleware berikutnya
         * @return Response
         */
        abstract public function process(Request $request, RequestHandlerInterface $handler): Response;
    }
?>