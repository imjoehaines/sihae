<?php

namespace Sihae;

use Sihae\Entities\User;
use Slim\Flash\Messages;
use Slim\Views\PhpRenderer;
use Sihae\Validators\Validator;
use Doctrine\ORM\EntityManager;
use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class RegistrationController
{
    private $renderer;
    private $validator;

    public function __construct(
        PhpRenderer $renderer,
        Validator $validator,
        EntityManager $entityManager,
        Messages $flash
    ) {
        $this->renderer = $renderer;
        $this->validator = $validator;
        $this->entityManager = $entityManager;
        $this->flash = $flash;
    }

    public function register(Request $request, Response $response) : Response
    {
        $userDetails = $request->getParsedBody();

        if (!$this->validator->isValid($userDetails)) {
            return $this->renderer->render($response, 'layout.phtml', [
                'page' => 'register',
                'errors' => $this->validator->getErrors(),
                'username' => $userDetails['username'],
            ]);
        }

        $user = new User;
        $user->setUsername($userDetails['username']);
        $user->setPassword($userDetails['password']);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $this->flash->addMessage('success', 'Successfully registered!');

        return $response->withStatus(302)->withHeader('Location', '/');
    }

    public function showForm(Request $request, Response $response) : Response
    {
        return $this->renderer->render($response, 'layout.phtml', ['page' => 'register']);
    }
}
