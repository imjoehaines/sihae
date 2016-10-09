<?php

namespace Sihae\Tests\Controllers;

use Prophecy\Prophet;
use Sihae\Entities\Post;
use Slim\Views\PhpRenderer;
use Doctrine\ORM\EntityManager;
use Sihae\Formatters\Formatter;
use PHPUnit\Framework\TestCase;
use Doctrine\ORM\EntityRepository;
use Sihae\Controllers\ArchiveController;
use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class ArchiveControllerTest extends TestCase
{
    public function testIndex()
    {
        $prophet = new Prophet();
        $renderer = $prophet->prophesize(PhpRenderer::class);
        $entityManager = $prophet->prophesize(EntityManager::class);
        $formatter = $prophet->prophesize(Formatter::class);
        $request = $prophet->prophesize(Request::class);
        $response = $prophet->prophesize(Response::class);

        $repository = $prophet->prophesize(EntityRepository::class);
        $repository->findBy([], ['date_created' => 'DESC'])->shouldBeCalled()->willReturn(['things']);

        $entityManager->getRepository(Post::class)->shouldBeCalled()->willReturn($repository->reveal());

        $formatter->format(['things'])->shouldBeCalled()->willReturn(['stuff']);

        $renderer->render($response, 'layout.phtml', [
            'page' => 'archive',
            'archiveData' => ['stuff'],
        ])->shouldBeCalled()->willReturn($response->reveal());

        $archiveController = new ArchiveController($renderer->reveal(), $entityManager->reveal(), $formatter->reveal());

        $actual = $archiveController->index($request->reveal(), $response->reveal());

        $this->assertSame($response->reveal(), $actual);

        $prophet->checkPredictions();
    }
}
