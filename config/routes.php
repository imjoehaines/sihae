<?php
// Routes

$app->get('/[{name}]', function ($request, $response, $name = null) {
    // Sample log message
    $this->logger->info("Slim-Skeleton '/' route");

    // Render index view
    return $this->renderer->render($response, 'index.phtml', ['name' => $name]);
});
