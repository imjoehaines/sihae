<?php

namespace Sihae\Middleware;

use RKA\Session;
use Sihae\Entities\User;
use Doctrine\ORM\EntityManager;
use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

/**
 * Checks the user in the current session is an admin, if they are not a 404
 * will be returned
 */
class AuthMiddleware
{
    /**
     * @var Session
     */
    private $session;

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @param Session $session
     */
    public function __construct(Session $session, EntityManager $entityManager)
    {
        $this->session = $session;
        $this->entityManager = $entityManager;
    }

    /**
     * Check the user in the current session is an admin, if they are not a 404
     * will be returned
     *
     * @param Request $request
     * @param Response $response
     * @param callable $next
     * @return Response
     */
    public function __invoke(Request $request, Response $response, callable $next) : Response
    {
        if ($username = $this->session->get('username')) {
            $user = $this->entityManager->getRepository(User::class)->findOneBy(['username' => $username]);

            if ($user->getIsAdmin() === true) {
                return $next($request, $response);
            }
        }

        return $response->withStatus(404);
    }
}
