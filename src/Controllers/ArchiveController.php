<?php

namespace Sihae\Controllers;

use Sihae\Entities\Post;
use Slim\Views\PhpRenderer;
use Doctrine\ORM\EntityManager;
use Sihae\Formatters\Formatter;
use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class ArchiveController
{
    /**
     * @var PhpRenderer
     */
    private $renderer;

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var Formatter
     */
    private $formatter;

    /**
     * @param PhpRenderer $renderer
     * @param EntityManager $entityManager
     * @param Formatter $formatter
     */
    public function __construct(
        PhpRenderer $renderer,
        EntityManager $entityManager,
        Formatter $formatter
    ) {
        $this->renderer = $renderer;
        $this->entityManager = $entityManager;
        $this->formatter = $formatter;
    }

    /**
     * List all Posts
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function index(Request $request, Response $response) : Response
    {
        $postRepository = $this->entityManager->getRepository(Post::class);

        $posts = $postRepository->findBy([], ['date_created' => 'DESC']);

        return $this->renderer->render($response, 'layout.phtml', [
            'page' => 'archive',
            'archiveData' => $this->formatter->format($posts),
        ]);
    }
}
