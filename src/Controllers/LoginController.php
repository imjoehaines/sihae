<?php declare(strict_types=1);

namespace Sihae\Controllers;

use RKA\Session;
use Sihae\Renderer;
use Sihae\Utils\Safe;
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
     * @param Renderer $renderer
     */
    public function __construct(Renderer $renderer)
    {
        $this->renderer = $renderer;
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
