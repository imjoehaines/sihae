<?php

namespace Sihae\Tests\Controllers;

use RKA\Session;
use Prophecy\Prophet;
use Slim\Http\Response;
use Slim\Flash\Messages;
use Sihae\Entities\Post;
use Slim\Views\PhpRenderer;
use Sihae\Validators\Validator;
use Doctrine\ORM\EntityManager;
use PHPUnit\Framework\TestCase;
use Doctrine\ORM\EntityRepository;
use Sihae\Controllers\PostController;
use League\CommonMark\CommonMarkConverter;
use Psr\Http\Message\RequestInterface as Request;

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
}
