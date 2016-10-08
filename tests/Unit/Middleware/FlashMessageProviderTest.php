<?php

namespace Sihae\Tests\Unit\Middleware;

use Prophecy\Prophet;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Flash\Messages;
use Slim\Views\PhpRenderer;
use PHPUnit\Framework\TestCase;
use Sihae\Middleware\FlashMessageProvider;

class FlashMessageProviderTest extends TestCase
{
    public function testItAddsASettingsAttributeToARenderer()
    {
        $prophet = new Prophet();
        $request = $prophet->prophesize(Request::class);
        $renderer = $prophet->prophesize(PhpRenderer::class);
        $flashMessages = $prophet->prophesize(Messages::class);
        $flashMessages->getMessages()->shouldBeCalled()->willReturn(['hello']);

        $renderer->addAttribute('flash_messages', ['hello'])->shouldBeCalled();

        $flashMessageProvider = new FlashMessageProvider($renderer->reveal(), $flashMessages->reveal());

        $next = function ($request, $response) {
            return $response;
        };

        $actual = $flashMessageProvider($request->reveal(), new Response, $next);

        $prophet->checkPredictions();

        // token assertion so PHPUnit doesn't mark this test as risky; the prophecy
        // checkPredictions call is the _real_ assertion
        $this->assertInstanceOf(Response::class, $actual);
    }
}
