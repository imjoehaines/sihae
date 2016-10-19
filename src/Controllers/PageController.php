<?php

namespace Sihae\Controllers;

use RKA\Session;
use Sihae\Renderer;
use Sihae\Entities\Page;
use Slim\Flash\Messages;
use Sihae\Validators\Validator;
use Doctrine\ORM\EntityManager;
use League\CommonMark\CommonMarkConverter;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use function Stringy\create as s;

/**
 * Controller for "static" pages (i.e. not blog posts)
 */
class PageController
{
    /**
     * @var Renderer
     */
    private $renderer;

    /**
     * @var CommonMarkConverter
     */
    private $markdown;

    /**
     * @var Messages
     */
    private $flash;

    /**
     * @var Validator
     */
    private $validator;

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @param Renderer $renderer
     * @param CommonMarkConverter $markdown
     * @param Messages $flash
     * @param Validator $validator
     * @param EntityManager $entityManager
     */
    public function __construct(
        Renderer $renderer,
        CommonMarkConverter $markdown,
        Messages $flash,
        Validator $validator,
        EntityManager $entityManager
    ) {
        $this->renderer = $renderer;
        $this->markdown = $markdown;
        $this->flash = $flash;
        $this->validator = $validator;
        $this->entityManager = $entityManager;
    }

    /**
     * Show form for creating a new Page
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function create(Request $request, Response $response) : Response
    {
        return $this->renderer->render($response, 'editor', ['type' => 'page']);
    }

    /**
     * Save a new Page
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function store(Request $request, Response $response) : Response
    {
        $newPage = $request->getParsedBody();

        $page = new Page();
        $page->setTitle($newPage['title']);
        $page->setBody($newPage['body']);

        if (!$this->validator->isValid($newPage)) {
            return $this->renderer->render($response, 'editor', [
                'type' => 'page',
                'entity' => $page,
                'errors' => $this->validator->getErrors(),
            ]);
        }

        $this->entityManager->persist($page);
        $this->entityManager->flush();

        $this->flash->addMessage('success', 'Successfully created your new page!');

        return $response->withStatus(302)->withHeader('Location', '/page/' . $page->getSlug());
    }

    /**
     * Show a single Page
     *
     * @param Request $request
     * @param Response $response
     * @param string $slug
     * @return Response
     */
    public function show(Request $request, Response $response, string $slug) : Response
    {
        $page = $this->entityManager->getRepository(Page::class)->findOneBy(['slug' => $slug]);

        if (!$page) {
            return $response->withStatus(404);
        }

        return $this->renderer->render($response, 'page', ['page' => $page]);
    }

    /**
     * Show the form to edit an existing Page
     *
     * @param Request $request
     * @param Response $response
     * @param string $slug
     * @return Response
     */
    public function edit(Request $request, Response $response, string $slug) : Response
    {
        throw new Exception('Not yet implemented');
    }

    /**
     * Save updates to an existing Page
     *
     * @param Request $request
     * @param Response $response
     * @param string $slug
     * @return Response
     */
    public function update(Request $request, Response $response, string $slug) : Response
    {
        throw new Exception('Not yet implemented');
    }

    /**
     * Delete a Page
     *
     * @param Request $request
     * @param Response $response
     * @param string $slug
     * @return Response
     */
    public function delete(Request $request, Response $response, string $slug) : Response
    {
        throw new Exception('Not yet implemented');
    }
}
