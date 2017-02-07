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
     * List all Tags
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
