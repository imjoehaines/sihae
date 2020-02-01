<?php declare(strict_types=1);

namespace Sihae\Middleware;

use Sihae\Renderer;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Provides the Sihae settings to the Renderer
 */
class SettingsProvider implements MiddlewareInterface
{
    /**
     * @var Renderer
     */
    private $renderer;

    /**
     * @var array<string, mixed>
     */
    private $settings;

    /**
     * @param Renderer $renderer
     * @param array<string, mixed> $settings
     */
    public function __construct(Renderer $renderer, array $settings)
    {
        $this->renderer = $renderer;
        $this->settings = $settings;
    }

    /**
     * Provide the Sihae settings to the Renderer
     *
     * @param Request $request
     * @param RequestHandlerInterface $next
     * @return ResponseInterface
     */
    public function process(Request $request, RequestHandlerInterface $next) : ResponseInterface
    {
        $this->renderer->addData(['settings' => $this->settings]);

        return $next->handle($request);
    }
}
