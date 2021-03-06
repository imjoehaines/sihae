<?php

declare(strict_types=1);

namespace Sihae;

use League\Plates\Engine;
use Psr\Http\Message\ResponseInterface;

/**
 * Wrapper around League\Plates\Engine to better support PSR-7 and theming
 */
final class Renderer
{
    private const THEME_PREFIX = 'theme::';

    private Engine $engine;

    public function __construct(Engine $engine)
    {
        $this->engine = $engine;
    }

    /**
     * @param ResponseInterface $response
     * @param string $template name of the template file to render
     * @param array<string, mixed> $data optional array of data to pass to the template
     * @return ResponseInterface
     */
    public function render(ResponseInterface $response, string $template, array $data = []): ResponseInterface
    {
        $body = $response->getBody();

        $templateName = static::THEME_PREFIX . $template;
        $body->write($this->engine->render($templateName, $data));

        return $response;
    }

    /**
     * Add data to the Engine instance
     *
     * @param array<string, mixed> $data
     * @return void
     */
    public function addData(array $data): void
    {
        $this->engine->addData($data);
    }
}
