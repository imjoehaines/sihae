<?php declare(strict_types=1);

namespace Sihae\Middleware;

use Sihae\Entities\Post;
use Nyholm\Psr7\Response;
use Slim\Routing\RouteContext;
use Doctrine\ORM\EntityManager;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
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
     * @param RequestHandlerInterface $next
     * @return ResponseInterface
     */
    public function process(Request $request, RequestHandlerInterface $next) : ResponseInterface
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

        return $next->handle($request->withAttribute('post', $post));
    }
}
