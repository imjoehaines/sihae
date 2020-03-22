<?php

declare(strict_types=1);

namespace Sihae\Actions;

use League\CommonMark\CommonMarkConverter;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Sihae\Entities\Post;
use Sihae\Renderer;

final class ViewPostAction implements RequestHandlerInterface
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
     * @var CommonMarkConverter
     */
    private CommonMarkConverter $markdown;

    /**
     * @param ResponseFactoryInterface $responseFactory
     * @param Renderer $renderer
     * @param CommonMarkConverter $markdown
     */
    public function __construct(
        ResponseFactoryInterface $responseFactory,
        Renderer $renderer,
        CommonMarkConverter $markdown
    ) {
        $this->responseFactory = $responseFactory;
        $this->renderer = $renderer;
        $this->markdown = $markdown;
    }

    /**
     * @param ServerRequestInterface $request
     *
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $post = $request->getAttribute('post');

        if (!$post instanceof Post) {
            return $this->responseFactory->createResponse(404);
        }

        $parsedBody = $this->markdown->convertToHtml($post->getBody());
        $post->setBody($parsedBody);

        return $this->renderer->render(
            $this->responseFactory->createResponse(),
            'post',
            ['post' => $post]
        );
    }
}
