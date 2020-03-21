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

final class EditPostAction implements RequestHandlerInterface
{
    /**
     * @var ResponseFactoryInterface
     */
    private $responseFactory;

    /**
     * @var PostRepository
     */
    private $postRepository;

    /**
     * @var TagRepository
     */
    private $tagRepository;

    /**
     * @var Validator
     */
    private $validator;

    /**
     * @var Renderer
     */
    private $renderer;

    /**
     * @param ResponseFactoryInterface $responseFactory
     * @param PostRepository $postRepository
     * @param TagRepository $tagRepository
     * @param Validator $validator
     * @param Renderer $renderer
     */
    public function __construct(
        ResponseFactoryInterface $responseFactory,
        PostRepository $postRepository,
        TagRepository $tagRepository,
        Validator $validator,
        Renderer $renderer
    ) {
        $this->responseFactory = $responseFactory;
        $this->postRepository = $postRepository;
        $this->tagRepository = $tagRepository;
        $this->validator = $validator;
        $this->renderer = $renderer;
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

        $updatedPost = $request->getParsedBody();

        // Set the title and body here so they are set if we render an error
        $post->setTitle(Safe::getString('title', $updatedPost, ''));
        $post->setBody(Safe::getString('body', $updatedPost, ''));

        if (!is_array($updatedPost)) {
            return $this->renderError($post);
        }

        $result = $this->validator->validate($updatedPost);

        if (!$result->isSuccess()) {
            return $this->renderError($post, $result->getErrors());
        }

        $post->clearTags();

        $tags = $this->tagRepository->findAll(
            $updatedPost['tags'] ?? [],
            $updatedPost['new_tags'] ?? []
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
    private function renderError(Post $post, array $errors = []): ResponseInterface
    {
        return $this->renderer->render(
            $this->responseFactory->createResponse(),
            'editor',
            [
                'post' => $post,
                'errors' => $errors,
                'isEdit' => true,
            ]
        );
    }
}
