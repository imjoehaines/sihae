<?php declare(strict_types=1);

namespace Sihae\Middleware;

use RKA\Session;
use Sihae\Entities\User;
use Doctrine\ORM\EntityManager;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

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
     * @param EntityManager $entityManager
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
        if ($token = $this->session->get('token')) {
            $user = $this->entityManager->getRepository(User::class)->findOneBy(['token' => $token]);

            if ($user && $user->getIsAdmin() === true) {
                return $next($request->withAttribute('user', $user), $response);
            }
        }

        return $response->withStatus(404);
    }
}
