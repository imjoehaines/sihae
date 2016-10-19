<?php

namespace Sihae\Middleware;

use Sihae\Renderer;
use Sihae\Entities\Page;
use Doctrine\ORM\EntityManager;
use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use function Stringy\create as s;

class PageProvider
{
    /**
     * @var Renderer
     */
    private $renderer;

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @param Renderer $renderer
     */
    public function __construct(Renderer $renderer, EntityManager $entityManager)
    {
        $this->renderer = $renderer;
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
        $pages = $this->entityManager->getRepository(Page::class)->findBy([]);

        $this->renderer->addData(['pages' => $pages]);

        return $next($request, $response);
    }
}
