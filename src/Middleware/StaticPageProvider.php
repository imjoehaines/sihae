<?php

namespace Sihae\Middleware;

use Sihae\Renderer;
use League\Flysystem\Filesystem;
use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use function Stringy\create as s;

class StaticPageProvider
{
    /**
     * @var Renderer
     */
    private $renderer;

    /**
     * @param Renderer $renderer
     */
    public function __construct(Renderer $renderer, Filesystem $staticDirectory)
    {
        $this->renderer = $renderer;
        $this->staticDirectory = $staticDirectory;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param callable $next
     * @return Response
     */
    public function __invoke(Request $request, Response $response, callable $next) : Response
    {
        $contents = $this->staticDirectory->listContents();

        $markdownFiles = array_filter($contents, function (array $file) : bool {
            return s($file['basename'])->endsWith('.md');
        });

        $pages = array_map(function (array $file) : array {
            return [
                'slug' => s($file['basename'])->trimRight('.md'),
                'title' => s($file['basename'])->replace('-', ' ')->toTitleCase()->trimRight('.md')
            ];
        }, $markdownFiles);

        $this->renderer->addData(['static_pages' => $pages]);

        return $next($request, $response);
    }
}
