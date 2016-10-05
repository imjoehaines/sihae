<?php

namespace Sihae;

use Slim\Views\PhpRenderer;
use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class AuthController
{
    private $renderer;

    public function __construct(PhpRenderer $renderer)
    {
        $this->renderer = $renderer;
    }

    public function login(Request $request, Response $response) : Response
    {
        return $response->withStatus(302)->withHeader('Location', '/');
    }

    public function logout(Request $request, Response $response) : Response
    {
        return $response->withStatus(302)->withHeader('Location', '/');
    }

    public function register(Request $request, Response $response) : Response
    {
        return $response->withStatus(302)->withHeader('Location', '/');
    }

    public function showLoginForm(Request $request, Response $response) : Response
    {
        return $this->renderer->render($response, 'layout.phtml', ['page' => 'login']);
    }

    public function showRegistrationForm(Request $request, Response $response) : Response
    {
        return $this->renderer->render($response, 'layout.phtml', ['page' => 'register']);
    }
}
