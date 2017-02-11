<?php declare(strict_types=1);

namespace Sihae\Controllers;

use Sihae\Renderer;
use Sihae\Entities\Post;
use Doctrine\ORM\EntityManager;
use Sihae\Formatters\Formatter;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Controller for the Archive page
 */
class ArchiveController
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
     * @var Formatter
     */
    private $formatter;

    /**
     * @param Renderer $renderer
     * @param EntityManager $entityManager
     * @param Formatter $formatter
     */
    public function __construct(
        Renderer $renderer,
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
        $posts = $this->entityManager->getRepository(Post::class)
            ->findBy(['is_page' => false], ['date_created' => 'DESC']);

        return $this->renderer->render($response, 'archive', [
            'archiveData' => $this->formatter->format($posts),
        ]);
    }
}
