<?php

namespace Sihae\Middleware;

use Slim\Csrf\Guard;
use Prophecy\Prophet;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Views\PhpRenderer;
use PHPUnit\Framework\TestCase;

class CsrfProviderTest extends TestCase
{
    public function testItAddsACsrfAttributeToRenderer()
    {
        $prophet = new Prophet();
        $csrf = $prophet->prophesize(Guard::class);
        $csrf->getTokenNameKey()->shouldBeCalled()->willReturn('token name key');
        $csrf->getTokenValueKey()->shouldBeCalled()->willReturn('token value key');

        $request = $prophet->prophesize(Request::class);
        $request->getAttribute('token name key')->shouldBeCalled()->willReturn('key attribute');
        $request->getAttribute('token value key')->shouldBeCalled()->willReturn('value attribute');

        $renderer = $prophet->prophesize(PhpRenderer::class);
        $renderer->addAttribute('csrf', [
            'nameKey' => 'token name key',
            'valueKey' => 'token value key',
            'name' => 'key attribute',
            'value' => 'value attribute',
        ])->shouldBeCalled();

        $csrfProvider = new CsrfProvider($renderer->reveal(), $csrf->reveal());

        $next = function ($request, $response) {
            return $response;
        };

        $response = new Response();
        $actual = $csrfProvider($request->reveal(), $response, $next);

        $this->assertSame($response, $actual);

        $prophet->checkPredictions();
    }
}
