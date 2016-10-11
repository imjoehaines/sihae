<?php

namespace Sihae\Tests\Controllers;

use RKA\Session;
use Prophecy\Prophet;
use Prophecy\Argument;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Flash\Messages;
use Sihae\Entities\Post;
use Sihae\Entities\User;
use Slim\Views\PhpRenderer;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\AbstractQuery;
use Sihae\Validators\Validator;
use Doctrine\ORM\EntityManager;
use PHPUnit\Framework\TestCase;
use Doctrine\ORM\EntityRepository;
use Sihae\Validators\PostValidator;
use Sihae\Controllers\PostController;
use League\CommonMark\CommonMarkConverter;

class PostControllerTest extends TestCase
{
    public function testCreate()
    {
        $prophet = new Prophet();

        $renderer = $prophet->prophesize(PhpRenderer::class);
        $entityManager = $prophet->prophesize(EntityManager::class);
        $markdown = $prophet->prophesize(CommonMarkConverter::class);
        $flash = $prophet->prophesize(Messages::class);
        $validator = $prophet->prophesize(Validator::class);

        $request = $prophet->prophesize(Request::class);
        $response = $prophet->prophesize(Response::class);

        $renderer->render($response, 'layout.phtml', [
            'page' => 'post-form',
        ])->shouldBeCalled()->willReturn($response->reveal());

        $postController = new PostController(
            $renderer->reveal(),
            $entityManager->reveal(),
            $markdown->reveal(),
            $flash->reveal(),
            $validator->reveal(),
            new Session()
        );

        $actual = $postController->create($request->reveal(), $response->reveal());

        $this->assertSame($response->reveal(), $actual);

        $prophet->checkPredictions();
    }

    public function testDelete404sWhenNoMatchingPostIsFound()
    {
        $prophet = new Prophet();

        $renderer = $prophet->prophesize(PhpRenderer::class);
        $entityManager = $prophet->prophesize(EntityManager::class);
        $markdown = $prophet->prophesize(CommonMarkConverter::class);
        $flash = $prophet->prophesize(Messages::class);
        $validator = $prophet->prophesize(Validator::class);

        $request = $prophet->prophesize(Request::class);

        $repository = $prophet->prophesize(EntityRepository::class);

        $entityManager->getRepository(Post::class)->shouldBeCalled()->willReturn($repository->reveal());
        $repository->findOneBy(['slug' => 'hello'])->shouldBeCalled()->willReturn(false);

        $postController = new PostController(
            $renderer->reveal(),
            $entityManager->reveal(),
            $markdown->reveal(),
            $flash->reveal(),
            $validator->reveal(),
            new Session()
        );

        $actual = $postController->delete($request->reveal(), new Response(), 'hello');

        $this->assertSame(404, $actual->getStatusCode());

        $prophet->checkPredictions();
    }

    public function testDeleteRemovesTheMatchingPost()
    {
        $prophet = new Prophet();

        $renderer = $prophet->prophesize(PhpRenderer::class);
        $entityManager = $prophet->prophesize(EntityManager::class);
        $markdown = $prophet->prophesize(CommonMarkConverter::class);
        $flash = $prophet->prophesize(Messages::class);
        $validator = $prophet->prophesize(Validator::class);

        $request = $prophet->prophesize(Request::class);

        $repository = $prophet->prophesize(EntityRepository::class);

        $post = new Post();

        $entityManager->getRepository(Post::class)->shouldBeCalled()->willReturn($repository->reveal());
        $repository->findOneBy(['slug' => 'hello'])->shouldBeCalled()->willReturn($post);

        $entityManager->remove($post)->shouldBeCalled();
        $entityManager->flush()->shouldBeCalled();
        $flash->addMessage('success', 'Successfully deleted your post!')->shouldBeCalled();

        $postController = new PostController(
            $renderer->reveal(),
            $entityManager->reveal(),
            $markdown->reveal(),
            $flash->reveal(),
            $validator->reveal(),
            new Session()
        );

        $actual = $postController->delete($request->reveal(), new Response(), 'hello');

        $this->assertSame(302, $actual->getStatusCode());

        $prophet->checkPredictions();
    }

    public function testUpdate404sWhenNoMatchingPostIsFound()
    {
        $prophet = new Prophet();

        $renderer = $prophet->prophesize(PhpRenderer::class);
        $entityManager = $prophet->prophesize(EntityManager::class);
        $markdown = $prophet->prophesize(CommonMarkConverter::class);
        $flash = $prophet->prophesize(Messages::class);
        $validator = $prophet->prophesize(Validator::class);

        $request = $prophet->prophesize(Request::class);

        $repository = $prophet->prophesize(EntityRepository::class);

        $entityManager->getRepository(Post::class)->shouldBeCalled()->willReturn($repository->reveal());
        $repository->findOneBy(['slug' => 'hello'])->shouldBeCalled()->willReturn(false);

        $postController = new PostController(
            $renderer->reveal(),
            $entityManager->reveal(),
            $markdown->reveal(),
            $flash->reveal(),
            $validator->reveal(),
            new Session()
        );

        $actual = $postController->update($request->reveal(), new Response(), 'hello');

        $this->assertSame(404, $actual->getStatusCode());

        $prophet->checkPredictions();
    }

    public function testUpdateReturnsToTheFormWhenTheMatchingPostIsNotValid()
    {
        $prophet = new Prophet();

        $renderer = $prophet->prophesize(PhpRenderer::class);
        $entityManager = $prophet->prophesize(EntityManager::class);
        $markdown = $prophet->prophesize(CommonMarkConverter::class);
        $flash = $prophet->prophesize(Messages::class);
        $validator = $prophet->prophesize(Validator::class);

        $updatedPost = ['title' => 'a', 'body' => 'b'];

        $request = $prophet->prophesize(Request::class);
        $request->getParsedBody()->shouldBeCalled()->willReturn($updatedPost);

        $validator->isValid($updatedPost)->shouldBeCalled()->willReturn(false);
        $validator->getErrors()->shouldBeCalled()->willReturn(['bad']);

        $post = new Post();
        $response = new Response();

        $renderer->render($response, 'layout.phtml', [
            'page' => 'post-form',
            'post' => $post,
            'errors' => ['bad'],
            'isEdit' => true,
        ])->shouldBeCalled()->willReturn($response);

        $repository = $prophet->prophesize(EntityRepository::class);

        $entityManager->getRepository(Post::class)->shouldBeCalled()->willReturn($repository->reveal());
        $repository->findOneBy(['slug' => 'hello'])->shouldBeCalled()->willReturn($post);

        $postController = new PostController(
            $renderer->reveal(),
            $entityManager->reveal(),
            $markdown->reveal(),
            $flash->reveal(),
            $validator->reveal(),
            new Session()
        );

        $actual = $postController->update($request->reveal(), $response, 'hello');

        $this->assertSame(200, $actual->getStatusCode());

        $prophet->checkPredictions();
    }

    public function testUpdateRedirectsToPostWhenSuccessful()
    {
        $prophet = new Prophet();

        $renderer = $prophet->prophesize(PhpRenderer::class);
        $entityManager = $prophet->prophesize(EntityManager::class);
        $markdown = $prophet->prophesize(CommonMarkConverter::class);
        $flash = $prophet->prophesize(Messages::class);
        $validator = $prophet->prophesize(Validator::class);

        $updatedPost = ['title' => 'a', 'body' => 'b'];

        $request = $prophet->prophesize(Request::class);
        $request->getParsedBody()->shouldBeCalled()->willReturn($updatedPost);

        $validator->isValid($updatedPost)->shouldBeCalled()->willReturn(true);

        $post = new Post();
        $response = new Response();

        $repository = $prophet->prophesize(EntityRepository::class);

        $entityManager->getRepository(Post::class)->shouldBeCalled()->willReturn($repository->reveal());
        $repository->findOneBy(['slug' => 'hello'])->shouldBeCalled()->willReturn($post);

        $entityManager->persist($post)->shouldBeCalled();
        $entityManager->flush()->shouldBeCalled();
        $flash->addMessage('success', 'Successfully edited your post!')->shouldBeCalled();

        $postController = new PostController(
            $renderer->reveal(),
            $entityManager->reveal(),
            $markdown->reveal(),
            $flash->reveal(),
            $validator->reveal(),
            new Session()
        );

        $actual = $postController->update($request->reveal(), $response, 'hello');

        $this->assertSame(302, $actual->getStatusCode());
        $this->assertSame(['Location' => ['/post/hello']], $actual->getHeaders());

        $prophet->checkPredictions();
    }

    public function testEdit404sWhenNoMatchingPostIsFound()
    {
        $prophet = new Prophet();

        $renderer = $prophet->prophesize(PhpRenderer::class);
        $entityManager = $prophet->prophesize(EntityManager::class);
        $markdown = $prophet->prophesize(CommonMarkConverter::class);
        $flash = $prophet->prophesize(Messages::class);
        $validator = $prophet->prophesize(Validator::class);

        $request = $prophet->prophesize(Request::class);

        $repository = $prophet->prophesize(EntityRepository::class);

        $entityManager->getRepository(Post::class)->shouldBeCalled()->willReturn($repository->reveal());
        $repository->findOneBy(['slug' => 'hello'])->shouldBeCalled()->willReturn(false);

        $postController = new PostController(
            $renderer->reveal(),
            $entityManager->reveal(),
            $markdown->reveal(),
            $flash->reveal(),
            $validator->reveal(),
            new Session()
        );

        $actual = $postController->edit($request->reveal(), new Response(), 'hello');

        $this->assertSame(404, $actual->getStatusCode());

        $prophet->checkPredictions();
    }

    public function testEditRendersEditFormWhenAMatchingPostIsFound()
    {
        $prophet = new Prophet();
        $post = new Post();
        $response = new Response();

        $renderer = $prophet->prophesize(PhpRenderer::class);
        $request = $prophet->prophesize(Request::class);
        $repository = $prophet->prophesize(EntityRepository::class);
        $entityManager = $prophet->prophesize(EntityManager::class);
        $entityManager->getRepository(Post::class)->shouldBeCalled()->willReturn($repository->reveal());
        $repository->findOneBy(['slug' => 'hello'])->shouldBeCalled()->willReturn($post);

        $renderer->render($response, 'layout.phtml', [
            'page' => 'post-form',
            'post' => $post,
            'isEdit' => true,
        ])->shouldBeCalled()->willReturn($response);

        $_SESSION = [];

        $postController = new PostController(
            $renderer->reveal(),
            $entityManager->reveal(),
            new CommonMarkConverter(),
            new Messages(),
            new PostValidator(),
            new Session()
        );

        $actual = $postController->edit($request->reveal(), $response, 'hello');

        $this->assertSame(200, $actual->getStatusCode());

        $prophet->checkPredictions();
        unset($_SESSION);
    }

    public function testShow404sWhenNoMatchingPostIsFound()
    {
        $prophet = new Prophet();

        $renderer = $prophet->prophesize(PhpRenderer::class);
        $entityManager = $prophet->prophesize(EntityManager::class);
        $markdown = $prophet->prophesize(CommonMarkConverter::class);
        $flash = $prophet->prophesize(Messages::class);
        $validator = $prophet->prophesize(Validator::class);

        $request = $prophet->prophesize(Request::class);

        $repository = $prophet->prophesize(EntityRepository::class);

        $entityManager->getRepository(Post::class)->shouldBeCalled()->willReturn($repository->reveal());
        $repository->findOneBy(['slug' => 'hello'])->shouldBeCalled()->willReturn(false);

        $postController = new PostController(
            $renderer->reveal(),
            $entityManager->reveal(),
            $markdown->reveal(),
            $flash->reveal(),
            $validator->reveal(),
            new Session()
        );

        $actual = $postController->show($request->reveal(), new Response(), 'hello');

        $this->assertSame(404, $actual->getStatusCode());

        $prophet->checkPredictions();
    }

    public function testShowRendersThePostWhenAMatchingPostIsFound()
    {
        $prophet = new Prophet();

        $response = new Response();

        $post = $prophet->prophesize(Post::class);
        $markdown = $prophet->prophesize(CommonMarkConverter::class);
        $renderer = $prophet->prophesize(PhpRenderer::class);
        $request = $prophet->prophesize(Request::class);
        $repository = $prophet->prophesize(EntityRepository::class);
        $entityManager = $prophet->prophesize(EntityManager::class);
        $entityManager->getRepository(Post::class)->shouldBeCalled()->willReturn($repository->reveal());
        $repository->findOneBy(['slug' => 'hello'])->shouldBeCalled()->willReturn($post->reveal());

        $markdown->convertToHtml('body')->shouldBeCalled()->willReturn('parsed body');
        $post->getBody()->shouldBeCalled()->willReturn('body');
        $post->setBody('parsed body')->shouldBeCalled()->willReturn($post->reveal());

        $renderer->render($response, 'layout.phtml', [
            'page' => 'post',
            'post' => $post->reveal(),
        ])->shouldBeCalled()->willReturn($response);

        $_SESSION = [];

        $postController = new PostController(
            $renderer->reveal(),
            $entityManager->reveal(),
            $markdown->reveal(),
            new Messages(),
            new PostValidator(),
            new Session()
        );

        $actual = $postController->show($request->reveal(), $response, 'hello');

        $this->assertSame(200, $actual->getStatusCode());

        $prophet->checkPredictions();
        unset($_SESSION);
    }

    public function testStoreRendersTheCreateFormWhenThePostIsInvalid()
    {
        $prophet = new Prophet();

        $renderer = $prophet->prophesize(PhpRenderer::class);
        $entityManager = $prophet->prophesize(EntityManager::class);
        $markdown = $prophet->prophesize(CommonMarkConverter::class);
        $flash = $prophet->prophesize(Messages::class);
        $validator = $prophet->prophesize(Validator::class);

        $postArray = ['title' => 'a', 'body' => 'b'];

        $request = $prophet->prophesize(Request::class);
        $request->getParsedBody()->shouldBeCalled()->willReturn($postArray);

        $validator->isValid($postArray)->shouldBeCalled()->willReturn(false);
        $validator->getErrors()->shouldBeCalled()->willReturn(['naughty']);

        $post = new Post();
        $post->setTitle('a');
        $post->setBody('b');

        $response = new Response();

        $renderer->render($response, 'layout.phtml', [
            'page' => 'post-form',
            'post' => $post,
            'errors' => ['naughty'],
        ])->shouldBeCalled()->willReturn($response);

        $postController = new PostController(
            $renderer->reveal(),
            $entityManager->reveal(),
            $markdown->reveal(),
            $flash->reveal(),
            $validator->reveal(),
            new Session()
        );

        $actual = $postController->store($request->reveal(), $response);

        $this->assertSame(200, $actual->getStatusCode());

        $prophet->checkPredictions();
    }

    public function testStorePersistsThePostAndRedirectsWhenThePostIsValid()
    {
        $prophet = new Prophet();

        $renderer = $prophet->prophesize(PhpRenderer::class);
        $entityManager = $prophet->prophesize(EntityManager::class);
        $markdown = $prophet->prophesize(CommonMarkConverter::class);
        $flash = $prophet->prophesize(Messages::class);
        $validator = $prophet->prophesize(Validator::class);

        $postArray = ['title' => 'a', 'body' => 'b'];

        $request = $prophet->prophesize(Request::class);
        $request->getParsedBody()->shouldBeCalled()->willReturn($postArray);

        $validator->isValid($postArray)->shouldBeCalled()->willReturn(true);

        $post = new Post();
        $post->setTitle('a');
        $post->setBody('b');

        $response = new Response();

        $user = new User();

        $repository = $prophet->prophesize(EntityRepository::class);

        $entityManager->getRepository(Post::class)->shouldBeCalled()->willReturn($repository->reveal());
        $repository->findOneBy(['slug' => 'a'])->shouldBeCalled()->willReturn(null);

        $entityManager->merge($user)->shouldBeCalled()->willReturn($user);
        $entityManager->persist(Argument::type(Post::class))->shouldBeCalled();
        $entityManager->flush()->shouldBeCalled();

        $flash->addMessage('success', 'Successfully created your new post!')->shouldBeCalled();

        $_SESSION = [];
        $session = new Session();
        $session->set('user', $user);

        $postController = new PostController(
            $renderer->reveal(),
            $entityManager->reveal(),
            $markdown->reveal(),
            $flash->reveal(),
            $validator->reveal(),
            $session
        );

        $actual = $postController->store($request->reveal(), $response);

        $this->assertSame(302, $actual->getStatusCode());
        $this->assertSame(['Location' => ['/post/a']], $actual->getHeaders());

        $prophet->checkPredictions();
        unset($_SESSION);
    }

    public function testIndex()
    {
        $prophet = new Prophet();

        $renderer = $prophet->prophesize(PhpRenderer::class);
        $entityManager = $prophet->prophesize(EntityManager::class);
        $markdown = $prophet->prophesize(CommonMarkConverter::class);
        $flash = $prophet->prophesize(Messages::class);
        $validator = $prophet->prophesize(Validator::class);

        $repository = $prophet->prophesize(EntityRepository::class);

        $post1 = new Post();
        $post1->setTitle('hello');
        $post1->setBody('hello *pal*');

        $post2 = new Post();
        $post2->setTitle('hey');
        $post2->setBody('hey _buddy_');

        $markdown->convertToHtml('hello *pal*')->shouldBeCalled()->willReturn('hello <strong>pal</strong>');
        $markdown->convertToHtml('hey _buddy_')->shouldBeCalled()->willReturn('hey <em>buddy</em>');

        $entityManager->getRepository(Post::class)->shouldBeCalled()->willReturn($repository->reveal());
        $repository->findBy([], ['date_created' => 'DESC'], 8, 0)->shouldBeCalled()->willReturn([$post1, $post2]);

        $queryBuilder = $prophet->prophesize(QueryBuilder::class);
        $query = $prophet->prophesize(AbstractQuery::class);

        $repository->createQueryBuilder('Post')->shouldBeCalled()->willReturn($queryBuilder->reveal());

        $queryBuilder->select('COUNT(Post.id)')->shouldBeCalled()->willReturn($queryBuilder->reveal());
        $queryBuilder->getQuery()->shouldBeCalled()->willReturn($query->reveal());
        $query->getSingleScalarResult()->shouldBeCalled()->willReturn(2);

        $response = new Response();
        $request = $prophet->prophesize(Request::class);

        $renderer->render($response, 'layout.phtml', Argument::type('array'))->shouldBeCalled()->willReturn($response);

        $postController = new PostController(
            $renderer->reveal(),
            $entityManager->reveal(),
            $markdown->reveal(),
            $flash->reveal(),
            $validator->reveal(),
            new Session()
        );

        $actual = $postController->index($request->reveal(), $response);

        $this->assertSame(200, $actual->getStatusCode());

        $prophet->checkPredictions();
        unset($_SESSION);
    }
}
