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

        $post->setTitle(Safe::get('title', $updatedPost, ''));
        $post->setBody(Safe::get('body', $updatedPost, ''));

        if (!is_array($updatedPost) || !$this->validator->isValid($updatedPost)) {
            return $this->renderer->render(
                $this->responseFactory->createResponse(),
                'editor',
                [
                    'post' => $post,
                    'errors' => $this->validator->getErrors(),
                    'isEdit' => true,
                ]
            );
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
}
