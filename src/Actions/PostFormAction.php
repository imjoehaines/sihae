<?php

declare(strict_types=1);

namespace Sihae\Actions;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Sihae\Renderer;
use Sihae\Repositories\TagRepository;

final class PostFormAction implements RequestHandlerInterface
{
    /**
     * @var ResponseFactoryInterface
     */
    private ResponseFactoryInterface $responseFactory;

    /**
     * @var Renderer
     */
    private Renderer $renderer;

    /**
     * @var TagRepository
     */
    private TagRepository $tagRepository;

    /**
     * @param ResponseFactoryInterface $responseFactory
     * @param Renderer $renderer
     * @param TagRepository $tagRepository
     */
    public function __construct(
        ResponseFactoryInterface $responseFactory,
        Renderer $renderer,
        TagRepository $tagRepository
    ) {
        $this->responseFactory = $responseFactory;
        $this->renderer = $renderer;
        $this->tagRepository = $tagRepository;
    }

    /**
     * @param ServerRequestInterface $request
     *
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $tags = $this->tagRepository->findAllAsArray();

        return $this->renderer->render(
            $this->responseFactory->createResponse(),
            'editor',
            ['tag_data' => json_encode(['tags' => $tags], JSON_THROW_ON_ERROR)]
        );
    }
}
