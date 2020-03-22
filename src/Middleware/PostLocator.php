<?php

declare(strict_types=1);

namespace Sihae\Middleware;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Sihae\Entities\Post;
use Sihae\Repositories\PostRepository;
use Slim\Routing\RouteContext;

/**
 * Finds a post by slug in the request
 */
final class PostLocator implements MiddlewareInterface
{
    private PostRepository $postRepository;
    private ResponseFactoryInterface $responseFactory;

    public function __construct(
        PostRepository $postRepository,
        ResponseFactoryInterface $responseFactory
    ) {
        $this->postRepository = $postRepository;
        $this->responseFactory = $responseFactory;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $route = $request->getAttribute(RouteContext::ROUTE);

        if ($route === null) {
            return $this->responseFactory->createResponse(404);
        }

        $slug = $route->getArgument('slug');

        $post = $this->postRepository->findBySlug($slug);

        if (!$post instanceof Post) {
            return $this->responseFactory->createResponse(404);
        }

        return $handler->handle($request->withAttribute('post', $post));
    }
}
