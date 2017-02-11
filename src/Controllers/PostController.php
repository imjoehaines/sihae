<?php declare(strict_types=1);

namespace Sihae\Controllers;

use RKA\Session;
use Sihae\Renderer;
use Doctrine\ORM\Query;
use Sihae\Entities\Tag;
use Sihae\Entities\Post;
use Sihae\Entities\User;
use Slim\Flash\Messages;
use Sihae\Validators\Validator;
use Doctrine\ORM\EntityManager;
use Sihae\Repositories\TagRepository;
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
     * @var EntityManager
     */
    private $entityManager;

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
     * @var Session
     */
    private $session;

    /**
     * @var TagRepository
     */
    private $tagRepository;

    /**
     * Strings that are used in routes and therefore can't be slugs
     *
     * @var array
     */
    private $reservedSlugs = ['new', 'edit', 'delete', 'login', 'logout', 'register', 'archive', 'convert'];

    /**
     * @param Renderer $renderer
     * @param EntityManager $entityManager
     * @param CommonMarkConverter $markdown
     * @param Messages $flash
     * @param Validator $validator
     * @param Session $session
     * @param TagRepository $tagRepository
     */
    public function __construct(
        Renderer $renderer,
        EntityManager $entityManager,
        CommonMarkConverter $markdown,
        Messages $flash,
        Validator $validator,
        Session $session,
        TagRepository $tagRepository
    ) {
        $this->renderer = $renderer;
        $this->entityManager = $entityManager;
        $this->markdown = $markdown;
        $this->flash = $flash;
        $this->validator = $validator;
        $this->session = $session;
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
        $query = $this->entityManager->createQuery(
            'SELECT partial t.{id, name}
             FROM Sihae\Entities\Tag t
             ORDER BY t.name DESC'
        );

        $tags = $query->getResult(Query::HYDRATE_ARRAY);

        return $this->renderer->render($response, 'editor', ['tags' => json_encode($tags)]);
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

        $post = new Post();
        $post->setTitle($newPost['title']);
        $post->setBody($newPost['body']);

        if (!$this->validator->isValid($newPost)) {
            return $this->renderer->render($response, 'editor', [
                'post' => $post,
                'errors' => $this->validator->getErrors(),
            ]);
        }

        $post->setUser($request->getAttribute('user'));

        // if there is already a post with the slug we just generated or the slug
        // is "reserved", generate a new one
        if (in_array($post->getSlug(), $this->reservedSlugs, true) ||
            $this->entityManager->getRepository(Post::class)->findOneBy(['slug' => $post->getSlug()])
        ) {
            $post->regenerateSlug();
        }

        $tags = $this->tagRepository->getAll(
            $updatedPost['tags'] ?? [],
            $updatedPost['new_tags'] ?? []
        );

        array_walk($tags, [$post, 'addTag']);

        $this->entityManager->persist($post);
        $this->entityManager->flush();

        $this->flash->addMessage('success', 'Successfully created your new post!');

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
        $post = $request->getAttribute('post');

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
        $post = $request->getAttribute('post');

        $query = $this->entityManager->createQuery(
            'SELECT partial t.{id, name}
             FROM Sihae\Entities\Tag t'
        );

        $tags = $query->getResult(Query::HYDRATE_ARRAY);

        $query = $this->entityManager->createQuery(
            'SELECT partial t.{id, name}
             FROM Sihae\Entities\Tag t
             JOIN t.posts p
             WHERE :post MEMBER OF t.posts
             GROUP BY t.id'
        );

        $query->setParameter('post', $post);
        $selectedTags = $query->getResult(Query::HYDRATE_ARRAY);

        return $this->renderer->render($response, 'editor', [
            'post' => $post,
            'isEdit' => true,
            'tags' => json_encode($tags),
            'selected_tags' => json_encode($selectedTags),
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
        $post = $request->getAttribute('post');

        $updatedPost = $request->getParsedBody();

        $post->setTitle($updatedPost['title']);
        $post->setBody($updatedPost['body']);

        if (!$this->validator->isValid($updatedPost)) {
            return $this->renderer->render($response, 'editor', [
                'post' => $post,
                'errors' => $this->validator->getErrors(),
                'isEdit' => true,
            ]);
        }

        $post->clearTags();

        $tags = $this->tagRepository->getAll(
            $updatedPost['tags'] ?? [],
            $updatedPost['new_tags'] ?? []
        );

        array_walk($tags, [$post, 'addTag']);

        $this->entityManager->persist($post);
        $this->entityManager->flush();

        $this->flash->addMessage('success', 'Successfully edited your post!');

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
        $post = $request->getAttribute('post');

        $this->entityManager->remove($post);
        $this->entityManager->flush();

        $this->flash->addMessage('success', 'Successfully deleted your post!');

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
        $entity = $this->entityManager->getRepository(Post::class)->findOneBy(['slug' => $slug]);

        $entity->setIsPage(!$entity->getIsPage());

        $this->entityManager->persist($entity);
        $this->entityManager->flush();

        $path = '/' . $entity->getSlug();

        if ($entity->getIsPage() === false) {
            $path = '/post' . $path;
        }

        return $response->withStatus(302)->withHeader('Location', $path);
    }
}
