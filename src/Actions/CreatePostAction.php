<?php

declare(strict_types=1);

namespace Sihae\Actions;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Sihae\Entities\Post;
use Sihae\Renderer;
use Sihae\Repositories\PostRepository;
use Sihae\Repositories\TagRepository;
use Sihae\Utils\Safe;
use Sihae\Validators\Validator;

final class CreatePostAction implements RequestHandlerInterface
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
    private $tagRepository;

    /**
     * @var PostRepository
     */
    private $postRepository;

    /**
     * @var Validator
     */
    private $validator;

    /**
     * Strings that are used in routes and therefore can't be slugs
     *
     * @var array<string>
     *
     * TODO remove this
     */
    private $reservedSlugs = ['new', 'edit', 'delete', 'login', 'logout', 'register', 'archive', 'convert'];

    /**
     * @param ResponseFactoryInterface $responseFactory
     * @param Renderer $renderer
     * @param TagRepository $tagRepository
     * @param PostRepository $postRepository
     * @param Validator $validator
     */
    public function __construct(
        ResponseFactoryInterface $responseFactory,
        Renderer $renderer,
        TagRepository $tagRepository,
        PostRepository $postRepository,
        Validator $validator
    ) {
        $this->responseFactory = $responseFactory;
        $this->renderer = $renderer;
        $this->tagRepository = $tagRepository;
        $this->validator = $validator;
        $this->postRepository = $postRepository;
    }

    /**
     * @param ServerRequestInterface $request
     *
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $newPost = $request->getParsedBody();

        $post = new Post(
            Safe::get('title', $newPost, ''),
            Safe::get('body', $newPost, ''),
            $request->getAttribute('user')
        );

        if (!is_array($newPost) || !$this->validator->isValid($newPost)) {
            return $this->renderer->render(
                $this->responseFactory->createResponse(),
                'editor',
                [
                    'post' => $post,
                    'errors' => $this->validator->getErrors(),
                ]
            );
        }

        // if there is already a post with the slug we just generated or the slug
        // is "reserved", generate a new one
        if (in_array($post->getSlug(), $this->reservedSlugs, true) ||
            $this->postRepository->findOneBySlug($post->getSlug()) !== null
        ) {
            $post->regenerateSlug();
        }

        $tags = $this->tagRepository->findAll(
            Safe::get('tags', $newPost, []),
            Safe::get('new_tags', $newPost, [])
        );

        foreach ($tags as $tag) {
            $post->addTag($tag);
        }

        $this->postRepository->save($post);

        return $this->responseFactory->createResponse(302)
            ->withHeader('Location', '/post/' . $post->getSlug());
    }
}
