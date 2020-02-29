<?php

declare(strict_types=1);

namespace Sihae\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Sihae\Entities\Post;
use Sihae\Renderer;
use Sihae\Repositories\PostRepository;
use Sihae\Repositories\TagRepository;
use Sihae\Utils\Safe;
use Sihae\Validators\Validator;

/**
 * Controller for handling creating/updating/deleting/showing blog posts
 */
class PostController
{
    /**
     * @var Renderer
     */
    private $renderer;

    /**
     * @var Validator
     */
    private $validator;

    /**
     * @var TagRepository
     */
    private $tagRepository;

    /**
     * @var PostRepository
     */
    private $postRepository;

    /**
     * @param Renderer $renderer
     * @param Validator $validator
     * @param PostRepository $postRepository
     * @param TagRepository $tagRepository
     */
    public function __construct(
        Renderer $renderer,
        Validator $validator,
        PostRepository $postRepository,
        TagRepository $tagRepository
    ) {
        $this->renderer = $renderer;
        $this->validator = $validator;
        $this->postRepository = $postRepository;
        $this->tagRepository = $tagRepository;
    }

    /**
     * Save updates to an existing Post
     *
     * @param Request $request
     * @param Response $response
     * @param string $slug
     * @return Response
     */
    public function update(Request $request, Response $response, string $slug): Response
    {
        $post = $this->getPost($request);

        $updatedPost = $request->getParsedBody();

        $post->setTitle(Safe::get('title', $updatedPost, ''));
        $post->setBody(Safe::get('body', $updatedPost, ''));

        if (!is_array($updatedPost) || !$this->validator->isValid($updatedPost)) {
            return $this->renderer->render($response, 'editor', [
                'post' => $post,
                'errors' => $this->validator->getErrors(),
                'isEdit' => true,
            ]);
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

        return $response->withStatus(302)->withHeader('Location', '/post/' . $slug);
    }

    /**
     * Convert a Post to a Page or vice versa
     *
     * @param Request $request
     * @param Response $response
     * @param string $slug
     * @return Response
     */
    public function convert(Request $request, Response $response, string $slug): Response
    {
        $entity = $this->postRepository->findOneBySlug($slug);

        if ($entity === null) {
            return $response->withStatus(404);
        }

        $entity->setIsPage(!$entity->isPage());

        $this->postRepository->save($entity);

        $path = '/' . $entity->getSlug();

        if ($entity->isPage() === false) {
            $path = '/post' . $path;
        }

        return $response->withStatus(302)->withHeader('Location', $path);
    }

    private function getPost(Request $request): Post
    {
        return $request->getAttribute('post');
    }
}
