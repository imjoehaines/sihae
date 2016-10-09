<?php

namespace Sihae\Middleware;

use RKA\Session;
use Slim\Views\PhpRenderer;
use Doctrine\ORM\EntityManager;
use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class UserProvider
{
    /**
     * @var PhpRenderer
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
     * @param PhpRenderer $renderer
     * @param Session $session
     * @param EntityManager $entityManager
     */
    public function __construct(PhpRenderer $renderer, Session $session, EntityManager $entityManager)
    {
        $this->renderer = $renderer;
        $this->session = $session;
        $this->entityManager = $entityManager;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param callable $next
     * @return Response
     */
    public function __invoke(Request $request, Response $response, callable $next) : Response
    {
        if ($user = $this->session->get('user')) {
            $user = $this->entityManager->merge($user);

            $this->renderer->addAttribute('user', $user);
        }

        return $next($request, $response);
    }
}
