<?php

declare(strict_types=1);

namespace Sihae\Actions;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Sihae\Renderer;
use Sihae\Repositories\TagRepository;

class TagListAction implements RequestHandlerInterface
{
    /**
     * @var ResponseFactoryInterface
     */
    private $responseFactory;

    /**
     * @var Renderer
     */
    private $renderer;

    /**
     * @var TagRepository
     */
    private $repository;

    /**
     * @param ResponseFactoryInterface $responseFactory
     * @param Renderer $renderer
     * @param TagRepository $repository
     */
    public function __construct(
        ResponseFactoryInterface $responseFactory,
        Renderer $renderer,
        TagRepository $repository
    ) {
        $this->responseFactory = $responseFactory;
        $this->renderer = $renderer;
        $this->repository = $repository;
    }

    /**
     * List all Tags ordered by the number of posts tagged with them
     *
     * For example if "PHP" has 10 posts, "JS" 4 and "Elixir" 1 then the order
     * will be "PHP", "JS", "Elixir"
     *
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $tags = $this->repository->findAllOrderedByUsage();
        $response = $this->responseFactory->createResponse();

        return $this->renderer->render($response, 'tags', ['tags' => $tags]);
    }
}
