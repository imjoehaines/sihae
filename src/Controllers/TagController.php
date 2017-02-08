<?php

namespace Sihae\Controllers;

use Sihae\Renderer;
use Sihae\Entities\Tag;
use Doctrine\ORM\EntityManager;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

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
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @param Renderer $renderer
     * @param EntityManager $entityManager
     */
    public function __construct(
        Renderer $renderer,
        EntityManager $entityManager
    ) {
        $this->renderer = $renderer;
        $this->entityManager = $entityManager;
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
    public function index(Request $request, Response $response) : Response
    {
        $dql =
            'SELECT t, p
             FROM Sihae\Entities\Tag t
             JOIN t.posts p';

        $tags = $this->entityManager->createQuery($dql)->getResult();

        usort($tags, function (Tag $a, Tag $b) : int {
            return $b->getPosts()->count() <=> $a->getPosts()->count();
        });

        return $this->renderer->render($response, 'tags', ['tags' => $tags]);
    }
}
