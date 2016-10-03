<?php

$app->get('/', 'Sihae\PostController:index');
$app->get('/post/new', 'Sihae\PostController:create');
$app->post('/post/new', 'Sihae\PostController:store');
$app->get('/post/edit/{slug}', 'Sihae\PostController:edit');
$app->post('/post/edit/{slug}', 'Sihae\PostController:update');
$app->get('/post/{slug}', 'Sihae\PostController:show');
