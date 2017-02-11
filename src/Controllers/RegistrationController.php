<?php declare(strict_types=1);

namespace Sihae\Controllers;

use RKA\Session;
use Sihae\Renderer;
use Sihae\Entities\User;
use Slim\Flash\Messages;
use Sihae\Validators\Validator;
use Doctrine\ORM\EntityManager;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Controller for registering new users
 */
class RegistrationController
{
    /**
     * @var Renderer
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
     * @param Renderer $renderer
     * @param Validator $validator
     * @param EntityManager $entityManager
     * @param Messages $flash
     * @param Session $session
     */
    public function __construct(
        Renderer $renderer,
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
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function register(Request $request, Response $response) : Response
    {
        $userDetails = $request->getParsedBody();

        if (!$this->validator->isValid($userDetails)) {
            return $this->renderer->render($response, 'register', [
                'errors' => $this->validator->getErrors(),
                'username' => $userDetails['username'],
            ]);
        }

        $user = new User;
        $user->setUsername($userDetails['username']);
        $user->setPassword($userDetails['password']);
        $user->setToken(bin2hex(random_bytes(128)));

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $this->session->set('token', $user->getToken());

        $this->flash->addMessage('success', 'Successfully registered!');

        return $response->withStatus(302)->withHeader('Location', '/');
    }

    /**
     * Show the registration form
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function showForm(Request $request, Response $response) : Response
    {
        if (!empty($this->session->get('token'))) {
            return $response->withStatus(302)->withHeader('Location', '/');
        }

        return $this->renderer->render($response, 'register');
    }
}
