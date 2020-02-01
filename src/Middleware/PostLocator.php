<?php declare(strict_types=1);

namespace Sihae\Middleware;

use Sihae\Entities\Post;
use Nyholm\Psr7\Response;
use Slim\Routing\RouteContext;
use Doctrine\ORM\EntityManager;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;

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
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param Request $request
     * @param RequestHandlerInterface $next
     * @return ResponseInterface
     */
    public function process(Request $request, RequestHandlerInterface $next) : ResponseInterface
    {
        $routeContext = RouteContext::fromRequest($request);
        $route = $routeContext->getRoute();

        if ($route === null) {
            return (new Response())->withStatus(404);
        }

        $slug = $route->getArgument('slug');

        $post = $this->entityManager->getRepository(Post::class)->findOneBy(['slug' => $slug]);

        if ($post === null) {
            return (new Response())->withStatus(404);
        }

        return $next->handle($request->withAttribute('post', $post));
    }
}
