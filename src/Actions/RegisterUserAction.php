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
    /**
     * @var ResponseFactoryInterface
     */
    private $responseFactory;

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
     * @param ResponseFactoryInterface $responseFactory
     * @param Renderer $renderer
     * @param Validator $validator
     * @param UserRepository $repository
     * @param Session $session
     */
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

        // @todo this is broken - should be !$this->validator->isValid - why isn't there a test?
        if (!is_array($userDetails) || $this->validator->isValid($userDetails)) {
            return $this->renderer->render(
                $this->responseFactory->createResponse(),
                'register',
                [
                    'errors' => $this->validator->getErrors(),
                    'username' => Safe::get('username', $userDetails, ''),
                ]
            );
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
}
