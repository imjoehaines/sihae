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
    private ResponseFactoryInterface $responseFactory;
    private Renderer $renderer;
    private TagRepository $tagRepository;
    private PostRepository $postRepository;
    private Validator $validator;

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

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $newPost = $request->getParsedBody();

        $post = new Post(
            Safe::getString('title', $newPost, ''),
            Safe::getString('body', $newPost, ''),
            $request->getAttribute('user')
        );

        if (!is_array($newPost)) {
            return $this->renderError($post, []);
        }

        $result = $this->validator->validate($newPost);

        if (!$result->isSuccess()) {
            return $this->renderError($post, $result->getErrors());
        }

        // if there is already a post with the slug we just generated, generate a new one
        if ($this->postRepository->findBySlug($post->getSlug()) !== null) {
            $post->regenerateSlug();
        }

        $tags = $this->tagRepository->findAll(
            Safe::getArray('tags', $newPost, []),
            Safe::getArray('new_tags', $newPost, [])
        );

        foreach ($tags as $tag) {
            $post->addTag($tag);
        }

        $this->postRepository->save($post);

        return $this->responseFactory->createResponse(302)
            ->withHeader('Location', '/post/' . $post->getSlug());
    }

    /**
     * @param Post $post
     * @param array<string> $errors
     * @return ResponseInterface
     */
    private function renderError(Post $post, array $errors): ResponseInterface
    {
        return $this->renderer->render(
            $this->responseFactory->createResponse(),
            'editor',
            [
                'post' => $post,
                'errors' => $errors,
            ]
        );
    }
}
