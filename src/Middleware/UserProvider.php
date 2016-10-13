<?php

namespace Sihae\Middleware;

use RKA\Session;
use Sihae\Renderer;
use Sihae\Entities\User;
use Doctrine\ORM\EntityManager;
use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

/**
 * Provides the logged in user to the Renderer
 */
class UserProvider
{
    /**
     * @var Renderer
     */
    private $renderer;

    /**
     * @var Session
     */
    private $session;

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @param Renderer $renderer
     * @param Session $session
     * @param EntityManager $entityManager
     */
    public function __construct(Renderer $renderer, Session $session, EntityManager $entityManager)
    {
        $this->renderer = $renderer;
        $this->session = $session;
        $this->entityManager = $entityManager;
    }

    /**
     * Provide the logged in user to the Renderer
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

            $this->renderer->addData(['user' => $user]);
        }

        return $next($request, $response);
    }
}
