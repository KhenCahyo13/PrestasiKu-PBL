<?php
    namespace App\Middlewares;

    use Psr\Http\Message\ResponseInterface;
    use Psr\Http\Message\ServerRequestInterface;
    use Psr\Http\Server\MiddlewareInterface;
    use Psr\Http\Server\RequestHandlerInterface;
    use Laminas\Diactoros\Response\RedirectResponse;

    class CheckAuthMiddleware implements MiddlewareInterface {
        public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            if (!isset($_SESSION['user'])) {
                return new RedirectResponse('/PrestasiKu-PBL/web/auth/login');
            }

            return $handler->handle($request);
        }
    }
?>