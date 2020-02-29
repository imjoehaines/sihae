<?php

declare(strict_types=1);

namespace Sihae\Actions;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Sihae\Entities\Post;
use Sihae\Repositories\PostRepository;

final class DeletePostAction implements RequestHandlerInterface
{
    /**
     * @var ResponseFactoryInterface
     */
    private $responseFactory;

    /**
     * @var PostRepository
     */
    private $postRepository;

    /**
     * @param ResponseFactoryInterface $responseFactory
     * @param PostRepository $postRepository
     */
    public function __construct(
        ResponseFactoryInterface $responseFactory,
        PostRepository $postRepository
    ) {
        $this->responseFactory = $responseFactory;
        $this->postRepository = $postRepository;
    }

    /**
     * @param ServerRequestInterface $request
     *
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $post = $request->getAttribute('post');

        if (!$post instanceof Post) {
            return $this->responseFactory->createResponse(404);
        }

        $this->postRepository->delete($post);

        return $this->responseFactory->createResponse(302)->withHeader('Location', '/');
    }
}
