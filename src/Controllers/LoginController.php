<?php declare(strict_types=1);

namespace Sihae\Controllers;

use RKA\Session;
use Sihae\Renderer;
use Sihae\Repositories\UserRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Controller for the login page
 */
class LoginController
{
    /**
     * @var Renderer
     */
    private $renderer;

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
     * @param UserRepository $repository
     * @param Session $session
     */
    public function __construct(
        Renderer $renderer,
        UserRepository $repository,
        Session $session
    ) {
        $this->renderer = $renderer;
        $this->repository = $repository;
        $this->session = $session;
    }

    /**
     * Log a user in
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function login(Request $request, Response $response) : Response
    {
        $userDetails = $request->getParsedBody();

        $user = $this->repository->findByUsername($userDetails['username'] ?? '');

        if ($user === null ||
            !$user->login($userDetails['password'] ?? '')
        ) {
            return $this->renderer->render($response, 'login', [
                'errors' => ['No user was found with these credentials, please try again'],
                'username' => $userDetails['username'] ?? '',
            ]);
        }

        $this->repository->save($user);

        $this->session->set('token', $user->getToken());

        return $response->withStatus(302)->withHeader('Location', '/');
    }

    /**
     * Log the current user out
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function logout(Request $request, Response $response) : Response
    {
        Session::destroy();

        return $response->withStatus(302)->withHeader('Location', '/');
    }

    /**
     * Show the login form
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function showForm(Request $request, Response $response) : Response
    {
        return $this->renderer->render($response, 'login');
    }
}
