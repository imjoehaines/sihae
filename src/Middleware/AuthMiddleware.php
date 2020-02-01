<?php declare(strict_types=1);

namespace Sihae\Middleware;

use RKA\Session;
use Nyholm\Psr7\Response;
use Sihae\Repositories\UserRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Checks the user in the current session is an admin, if they are not a 404
 * will be returned
 */
class AuthMiddleware implements MiddlewareInterface
{
    /**
     * @var Session
     */
    private $session;

    /**
     * @var UserRepository
     */
    private $repository;

    /**
     * @param Session $session
     * @param UserRepository $repository
     */
    public function __construct(Session $session, UserRepository $repository)
    {
        $this->session = $session;
        $this->repository = $repository;
    }

    /**
     * Check the user in the current session is an admin, if they are not a 404
     * will be returned
     *
     * @param Request $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(Request $request, RequestHandlerInterface $handler) : ResponseInterface
    {
        $token = $this->session->get('token');

        if ($token) {
            $user = $this->repository->findByToken($token);

            if ($user !== null && $user->isAdmin() === true) {
                return $handler->handle($request->withAttribute('user', $user));
            }
        }

        return (new Response())->withStatus(404);
    }
}
