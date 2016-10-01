<?php

use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

// 404 Handler
$app->add(function (Request $request, Response $response, callable $next) use ($container) {
    $response = $next($request, $response);

    if ($response->getStatusCode() == 404) {
        $handler = $container['notFoundHandler'];

        return $handler($request, $response);
    }

    return $response;
});
