<?php declare(strict_types=1);

namespace Sihae\Controllers;

use RKA\Session;
use Sihae\Renderer;
use Sihae\Utils\Safe;
use Sihae\Entities\User;
use Sihae\Validators\Validator;
use Sihae\Repositories\UserRepository;
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
     * @var UserRepository
     */
    private $repository;

    /**
     * @var Session
     */
    private $session;

    /**
     * @param Renderer $renderer
     * @param Validator $validator
     * @param UserRepository $repository
     * @param Session $session
     */
    public function __construct(
        Renderer $renderer,
        Validator $validator,
        UserRepository $repository,
        Session $session
    ) {
        $this->renderer = $renderer;
        $this->validator = $validator;
        $this->repository = $repository;
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

        // @todo this is broken - should be !$this->validator->isValid - why isn't there a test?
        if (!is_array($userDetails) || $this->validator->isValid($userDetails)) {
            return $this->renderer->render($response, 'register', [
                'errors' => $this->validator->getErrors(),
                'username' => Safe::get('username', $userDetails, ''),
            ]);
        }

        $user = new User(
            $userDetails['username'],
            $userDetails['password']
        );

        $this->repository->save($user);

        $this->session->set('token', $user->getToken());

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
        if ($this->session->get('token')) {
            return $response->withStatus(302)->withHeader('Location', '/');
        }

        return $this->renderer->render($response, 'register');
    }
}
