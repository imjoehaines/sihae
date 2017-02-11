<?php declare(strict_types=1);

namespace Sihae\Api\V1\Controllers;

use Slim\Csrf\Guard;
use League\CommonMark\CommonMarkConverter;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ApiController
{
    /**
     * @var CommonMarkConverter
     */
    private $markdown;

    /**
     * @var Guard
     */
    private $csrf;

    /**
     * @param CommonMarkConverter $markdown
     * @param Guard $csrf
     */
    public function __construct(
        CommonMarkConverter $markdown,
        Guard $csrf
    ) {
        $this->markdown = $markdown;
        $this->csrf = $csrf;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function render(Request $request, Response $response) : Response
    {
        $parsedBody = $request->getParsedBody();
        $post = $parsedBody['post'];

        $html = $this->markdown->convertToHtml($post);

        $csrfKey = $this->csrf->getTokenName();
        $csrfValue = $this->csrf->getTokenValue();

        $body = $response->getBody();
        $body->write(json_encode([
            'html' => $html,
            'csrfKey' => $csrfKey,
            'csrfValue' => $csrfValue,
        ]));

        return $response->withHeader('Content-Type', 'application/json');
    }
}
