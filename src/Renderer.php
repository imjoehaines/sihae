<?php

namespace Sihae;

use League\Plates\Engine;
use Psr\Http\Message\ResponseInterface as Response;

class Renderer
{
    private $engine;

    public function __construct(Engine $engine)
    {
        $this->engine = $engine;
    }

    public function render(Response $response, string $template, array $data = []) : Response
    {
        $body = $response->getBody();
        $body->write($this->engine->render($template, $data));

        return $response;
    }

    public function addData(array $data)
    {
        $this->engine->addData($data);
    }
}
