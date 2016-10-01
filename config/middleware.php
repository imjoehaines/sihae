<?php

use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

// 404 Handler
$app->add(function (Request $request, Response $response, callable $next) use ($container) {
    $response = $next($request, $response);

    if ($response->getStatusCode() == 404) {
        $handler = $container->get('notFoundHandler');

        return $handler($request, $response);
    }

    return $response;
});

// provide $settings to all views
$app->add(function (Request $request, Response $response, callable $next) use ($container) {
    $settings = $container->get('settings')['sihae'];
    $container->get('renderer')->addAttribute('settings', $settings);

    return $next($request, $response);
});
