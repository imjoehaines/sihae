<?php

namespace Sihae\Tests\Unit\Middleware;

use Prophecy\Prophet;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Views\PhpRenderer;
use PHPUnit\Framework\TestCase;
use Sihae\Middleware\SettingsProvider;

class SettingsProviderTest extends TestCase
{
    public function testItAddsASettingsAttributeToARenderer()
    {
        $prophet = new Prophet();
        $request = $prophet->prophesize(Request::class);
        $renderer = $prophet->prophesize(PhpRenderer::class);

        $renderer->addAttribute('settings', ['hello'])->shouldBeCalled();

        $settingsProvider = new SettingsProvider($renderer->reveal(), ['hello']);

        $next = function ($request, $response) {
            return $response;
        };

        $actual = $settingsProvider($request->reveal(), new Response, $next);

        $prophet->checkPredictions();

        // token assertion so PHPUnit doesn't mark this test as risky; the prophecy
        // checkPredictions call is the _real_ assertion
        $this->assertInstanceOf(Response::class, $actual);
    }
}
