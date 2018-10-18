<?php declare (strict_types = 1);

namespace App\Middlewares;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\RedirectResponse;
use Zend\Diactoros\Response\JsonResponse;

class AuthMiddleware implements MiddlewareInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
        // determine authentication and/or authorization
        // ...
        $auth = false;

        // if user has auth, use the request handler to continue to the next
        // middleware and ultimately reach your route callable
        if ($auth === true) {
            return $handler->handle($request);
        }

        // if user does not have auth, possibly return a redirect response,
        // this will not continue to any further middleware and will never
        // reach your route callable
        // return new RedirectResponse('/');
        return new JsonResponse(['error' => 'Unauthenticated.'], 401);
    }
}
