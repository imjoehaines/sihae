<?php

namespace Sihae;

use RKA\Session;
use Sihae\Entities\User;
use Slim\Flash\Messages;
use Slim\Views\PhpRenderer;
use Doctrine\ORM\EntityManager;
use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class LoginController
{
    private $renderer;
    private $validator;
    private $flash;
    private $session;

    public function __construct(
        PhpRenderer $renderer,
        EntityManager $entityManager,
        Messages $flash,
        Session $session
    ) {
        $this->renderer = $renderer;
        $this->entityManager = $entityManager;
        $this->flash = $flash;
        $this->session = $session;
    }

    public function login(Request $request, Response $response) : Response
    {
        $userDetails = $request->getParsedBody();

        $user = $this->entityManager->getRepository(User::class)
            ->findOneBy(['username' => $userDetails['username']]);

        if (!$user || !password_verify($userDetails['password'], $user->getPassword())) {
            return $this->renderer->render($response, 'layout.phtml', [
                'page' => 'login',
                'errors' => ['No user was found with these credentials, please try again'],
                'username' => $userDetails['username'],
            ]);
        }

        $this->session->set('username', $user->getUsername());

        $this->flash->addMessage('success', 'Welcome back ' . $user->getUsername());

        return $response->withStatus(302)->withHeader('Location', '/');
    }

    public function logout(Request $request, Response $response) : Response
    {
        Session::destroy();

        return $response->withStatus(302)->withHeader('Location', '/');
    }

    public function showForm(Request $request, Response $response) : Response
    {
        return $this->renderer->render($response, 'layout.phtml', ['page' => 'login']);
    }
}
