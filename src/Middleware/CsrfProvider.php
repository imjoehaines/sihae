<?php declare(strict_types=1);

namespace Sihae\Middleware;

use Sihae\Renderer;
use Slim\Csrf\Guard;
use Nyholm\Psr7\Response;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Provides data for CSRF protection to the Renderer
 */
class CsrfProvider implements MiddlewareInterface
{
    /**
     * @var Renderer
     */
    private $renderer;

    /**
     * @var Guard
     */
    private $csrf;

    /**
     * @param Renderer $renderer
     * @param Guard $csrf
     */
    public function __construct(Renderer $renderer, Guard $csrf)
    {
        $this->renderer = $renderer;
        $this->csrf = $csrf;
    }

    /**
     * Provide data for CSRF protection to the Renderer
     *
     * @param Request $request
     * @param RequestHandlerInterface $handler
     * @return Response
     */
    public function process(Request $request, RequestHandlerInterface $handler) : Response
    {
        $nameKey = $this->csrf->getTokenNameKey();
        $valueKey = $this->csrf->getTokenValueKey();

        $this->renderer->addData(['csrf' => [
            'nameKey' => $nameKey,
            'valueKey' => $valueKey,
            'name' => $request->getAttribute($nameKey),
            'value' => $request->getAttribute($valueKey),
        ]]);

        return $handler->handle($request);
    }
}
