<?php declare(strict_types=1);

namespace Sihae;

use League\Plates\Engine;
use Psr\Http\Message\ResponseInterface as Response;

/**
 * Wrapper around League\Plates\Engine to better support PSR-7
 */
class Renderer
{
    /**
     * @var Engine
     */
    private $engine;

    /**
     * @param Engine $engine
     */
    public function __construct(Engine $engine)
    {
        $this->engine = $engine;
    }

    /**
     * @param Response $response
     * @param string $template name of the template file to render
     * @param array $data optional array of data to pass to the template
     * @return Response
     */
    public function render(Response $response, string $template, array $data = []) : Response
    {
        $body = $response->getBody();
        $body->write($this->engine->render($template, $data));

        return $response;
    }

    /**
     * Add data to the Engine instance
     *
     * @param array $data
     * @return void
     */
    public function addData(array $data) : void
    {
        $this->engine->addData($data);
    }
}
