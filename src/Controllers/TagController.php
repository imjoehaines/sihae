<?php

declare(strict_types=1);

namespace Sihae\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Sihae\Renderer;
use Sihae\Repositories\TagRepository;

/**
 * Controller for the Tag page
 */
class TagController
{
    /**
     * @var Renderer
     */
    private $renderer;

    /**
     * @var TagRepository
     */
    private $repository;

    /**
     * @param Renderer $renderer
     * @param TagRepository $repository
     */
    public function __construct(
        Renderer $renderer,
        TagRepository $repository
    ) {
        $this->renderer = $renderer;
        $this->repository = $repository;
    }

    /**
     * List all Tags ordered by the number of posts tagged with them
     *
     * For example if "PHP" has 10 posts, "JS" 4 and "Elixir" 1 then the order
     * will be "PHP", "JS", "Elixir"
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function index(Request $request, Response $response): Response
    {
        $tags = $this->repository->findAllOrderedByUsage();

        return $this->renderer->render($response, 'tags', ['tags' => $tags]);
    }
}
