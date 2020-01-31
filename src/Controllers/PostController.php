<?php declare(strict_types=1);

namespace Sihae\Controllers;

use Sihae\Renderer;
use Sihae\Utils\Safe;
use Doctrine\ORM\Query;
use Sihae\Entities\Tag;
use Sihae\Entities\Post;
use Sihae\Entities\User;
use Sihae\Validators\Validator;
use Sihae\Repositories\TagRepository;
use Sihae\Repositories\PostRepository;
use League\CommonMark\CommonMarkConverter;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

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
     * @var CommonMarkConverter
     */
    private $markdown;

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
     * Strings that are used in routes and therefore can't be slugs
     *
     * @var array<string>
     */
    private $reservedSlugs = ['new', 'edit', 'delete', 'login', 'logout', 'register', 'archive', 'convert'];

    /**
     * @param Renderer $renderer
     * @param CommonMarkConverter $markdown
     * @param Validator $validator
     * @param PostRepository $postRepository
     * @param TagRepository $tagRepository
     */
    public function __construct(
        Renderer $renderer,
        CommonMarkConverter $markdown,
        Validator $validator,
        PostRepository $postRepository,
        TagRepository $tagRepository
    ) {
        $this->renderer = $renderer;
        $this->markdown = $markdown;
        $this->validator = $validator;
        $this->postRepository = $postRepository;
        $this->tagRepository = $tagRepository;
    }

    /**
     * Show form for creating a new Post
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function create(Request $request, Response $response) : Response
    {
        $tags = $this->tagRepository->findAllAsArray();

        return $this->renderer->render($response, 'editor', [
            'tag_data' => json_encode(['tags' => $tags]),
        ]);
    }

    /**
     * Save a new Post
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function store(Request $request, Response $response) : Response
    {
        $newPost = $request->getParsedBody();

        $post = new Post(
            Safe::get('title', $newPost, ''),
            Safe::get('body', $newPost, ''),
            $request->getAttribute('user')
        );

        if (!is_array($newPost) || !$this->validator->isValid($newPost)) {
            return $this->renderer->render($response, 'editor', [
                'post' => $post,
                'errors' => $this->validator->getErrors(),
            ]);
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

        return $response->withStatus(302)->withHeader('Location', '/post/' . $post->getSlug());
    }

    /**
     * Show a single Post
     *
     * @param Request $request
     * @param Response $response
     * @param string $slug
     * @return Response
     */
    public function show(Request $request, Response $response, string $slug) : Response
    {
        $post = $this->getPost($request);

        $parsedBody = $this->markdown->convertToHtml($post->getBody());
        $post->setBody($parsedBody);

        return $this->renderer->render($response, 'post', ['post' => $post]);
    }

    /**
     * Show the form to edit an existing Post
     *
     * @param Request $request
     * @param Response $response
     * @param string $slug
     * @return Response
     */
    public function edit(Request $request, Response $response, string $slug) : Response
    {
        $post = $this->getPost($request);

        $tags = $this->tagRepository->findAllAsArray();

        $selectedTags = $this->tagRepository->findAllForPostAsArray($post);

        return $this->renderer->render($response, 'editor', [
            'post' => $post,
            'isEdit' => true,
            'tag_data' => json_encode([
                'tags' => $tags,
                'selected_tags' => $selectedTags,
            ]),
        ]);
    }

    /**
     * Save updates to an existing Post
     *
     * @param Request $request
     * @param Response $response
     * @param string $slug
     * @return Response
     */
    public function update(Request $request, Response $response, string $slug) : Response
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
     * Delete a Post
     *
     * @param Request $request
     * @param Response $response
     * @param string $slug
     * @return Response
     */
    public function delete(Request $request, Response $response, string $slug) : Response
    {
        $post = $this->getPost($request);

        $this->postRepository->delete($post);

        return $response->withStatus(302)->withHeader('Location', '/');
    }

    /**
     * Convert a Post to a Page or vice versa
     *
     * @param Request $request
     * @param Response $response
     * @param string $slug
     * @return Response
     */
    public function convert(Request $request, Response $response, string $slug) : Response
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

    private function getPost(Request $request) : Post
    {
        return $request->getAttribute('post');
    }
}
