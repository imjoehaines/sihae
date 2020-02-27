<?php

declare(strict_types=1);

namespace Sihae\Middleware;

use Doctrine\ORM\EntityManager;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Sihae\Entities\Post;
use Slim\Routing\RouteContext;

/**
 * Finds a post by slug in the request
 */
class PostLocator implements MiddlewareInterface
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var ResponseFactoryInterface
     */
    private $responseFactory;

    /**
     * @param EntityManager $entityManager
     * @param ResponseFactoryInterface $responseFactory
     */
    public function __construct(
        EntityManager $entityManager,
        ResponseFactoryInterface $responseFactory
    ) {
        $this->entityManager = $entityManager;
        $this->responseFactory = $responseFactory;
    }

    /**
     * @param Request $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(Request $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $route = $request->getAttribute(RouteContext::ROUTE);

        if ($route === null) {
            return $this->responseFactory->createResponse(404);
        }

        $slug = $route->getArgument('slug');

        $post = $this->entityManager->getRepository(Post::class)->findOneBy(['slug' => $slug]);

        if ($post === null) {
            return $this->responseFactory->createResponse(404);
        }

        return $handler->handle($request->withAttribute('post', $post));
    }
}
