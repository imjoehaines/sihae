<?php

namespace Sihae\Middleware;

use Sihae\Entities\Post;
use Doctrine\ORM\EntityManager;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Finds a post by slug in the request
 */
class PostLocator
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param callable $next
     * @return Response
     */
    public function __invoke(Request $request, Response $response, callable $next) : Response
    {
        $route = $request->getAttribute('route');
        $slug = $route->getArgument('slug');

        $post = $this->entityManager->getRepository(Post::class)->findOneBy(['slug' => $slug]);

        if ($post) {
            return $next($request->withAttribute('post', $post), $response);
        }

        return $response->withStatus(404);
    }
}
