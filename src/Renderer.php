<?php declare(strict_types=1);

namespace Sihae;

use League\Plates\Engine;
use Psr\Http\Message\ResponseInterface as Response;

/**
 * Wrapper around League\Plates\Engine to better support PSR-7 and theming
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

        $templateName = $this->getTemplateName($template);
        $body->write($this->engine->render($templateName, $data));

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

    /**
     * Get the "real" template name - if a custom theme is being used then this
     * will need to be prefixed with "theme" so that Plates will use it
     *
     * @param string $template
     * @return string
     */
    private function getTemplateName(string $template) : string
    {
        return $this->useCustomTheme() ? 'theme::' . $template : $template;
    }

    /**
     * Determine whether a custom theme is enabled - if the "theme" folder exists
     * in Plates' Engine then it has been registered and so should be used
     *
     * @return bool
     */
    private function useCustomTheme() : bool
    {
        return $this->engine->getFolders()->exists('theme');
    }
}
