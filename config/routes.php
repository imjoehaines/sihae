<?php
// Routes

$app->get('/[{name}]', function ($request, $response, $name = null) {
    return $this->renderer->render($response, 'layout.phtml', [
        'name' => $name,
        'page' => 'a',
    ]);
});
