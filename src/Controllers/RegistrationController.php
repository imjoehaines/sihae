<?php

namespace Sihae\Controllers;

use RKA\Session;
use Sihae\Entities\User;
use Slim\Flash\Messages;
use Slim\Views\PhpRenderer;
use Sihae\Validators\Validator;
use Doctrine\ORM\EntityManager;
use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class RegistrationController
{
    /**
     * @var PhpRenderer
     */
    private $renderer;

    /**
     * @var Validator
     */
    private $validator;

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var Messages
     */
    private $flash;

    /**
     * @var Session
     */
    private $session;

    /**
     * @param PhpRenderer $renderer
     * @param Validator $validator
     * @param EntityManager $entityManager
     * @param Messages $flash
     * @param Session $session
     */
    public function __construct(
        PhpRenderer $renderer,
        Validator $validator,
        EntityManager $entityManager,
        Messages $flash,
        Session $session
    ) {
        $this->renderer = $renderer;
        $this->validator = $validator;
        $this->entityManager = $entityManager;
        $this->flash = $flash;
        $this->session = $session;
    }

    /**
     * Register a new user.
     *
     * TODO This should be turned off once a user has been registered or a config
     * value is set and throw if called after this condition
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     */
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

        $this->entityManager->detach($user);
        $this->session->set('user', $user);

        $this->flash->addMessage('success', 'Successfully registered!');

        return $response->withStatus(302)->withHeader('Location', '/');
    }

    /**
     * Show the registration form
     *
     * TODO This should be turned off once a user has been registered or a config
     * value is set and throw if called after this condition
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function showForm(Request $request, Response $response) : Response
    {
        if (!empty($this->session->get('user'))) {
            return $response->withStatus(302)->withHeader('Location', '/');
        }

        return $this->renderer->render($response, 'layout.phtml', ['page' => 'register']);
    }
}
