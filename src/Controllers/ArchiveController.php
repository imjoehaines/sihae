<?php declare(strict_types=1);

namespace Sihae\Controllers;

use Sihae\Renderer;
use Sihae\Entities\Post;
use Sihae\Formatters\Formatter;
use Sihae\Repositories\PostRepository;
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
     * @var PostRepository
     */
    private $repository;

    /**
     * @var Formatter
     */
    private $formatter;

    /**
     * @param Renderer $renderer
     * @param PostRepository $repository
     * @param Formatter $formatter
     */
    public function __construct(
        Renderer $renderer,
        PostRepository $repository,
        Formatter $formatter
    ) {
        $this->renderer = $renderer;
        $this->repository = $repository;
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
        $posts = $this->repository->findAllOrderedByDateCreated();

        return $this->renderer->render($response, 'archive', [
            'archiveData' => $this->formatter->format($posts),
        ]);
    }
}
