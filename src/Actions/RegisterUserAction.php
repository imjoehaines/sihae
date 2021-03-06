<?php

declare(strict_types=1);

namespace Sihae\Actions;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use RKA\Session;
use Sihae\Entities\User;
use Sihae\Renderer;
use Sihae\Repositories\UserRepository;
use Sihae\Utils\Safe;
use Sihae\Validators\Validator;

final class RegisterUserAction implements RequestHandlerInterface
{
    private ResponseFactoryInterface $responseFactory;
    private Renderer $renderer;
    private Validator $validator;
    private UserRepository $repository;
    private Session $session;

    public function __construct(
        ResponseFactoryInterface $responseFactory,
        Renderer $renderer,
        Validator $validator,
        UserRepository $repository,
        Session $session
    ) {
        $this->responseFactory = $responseFactory;
        $this->renderer = $renderer;
        $this->validator = $validator;
        $this->repository = $repository;
        $this->session = $session;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $userDetails = $request->getParsedBody();
        $username = Safe::getString('username', $userDetails, '');

        if (!is_array($userDetails)) {
            return $this->renderError($username);
        }

        $result = $this->validator->validate($userDetails);

        if (!$result->isSuccess()) {
            return $this->renderError($username, $result->getErrors());
        }

        $user = new User(
            $userDetails['username'],
            $userDetails['password']
        );

        $this->repository->save($user);

        $this->session->set('token', $user->getToken());

        return $this->responseFactory->createResponse(302)
            ->withHeader('Location', '/');
    }

    /**
     * @param string $username
     * @param array<string> $errors
     * @return ResponseInterface
     */
    private function renderError(string $username, array $errors = []): ResponseInterface
    {
        return $this->renderer->render(
            $this->responseFactory->createResponse(),
            'register',
            [
                'errors' => $errors,
                'username' => $username,
            ]
        );
    }
}
