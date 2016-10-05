<?php

use Slim\App;

return function (App $app) {
    $app->get('/[page/{page:[1-9][0-9]*}]', 'Sihae\PostController:index');

    $app->get('/post/new', 'Sihae\PostController:create');
    $app->post('/post/new', 'Sihae\PostController:store');
    $app->get('/post/edit/{slug:[a-zA-Z\d\s-_\-]+}', 'Sihae\PostController:edit');
    $app->post('/post/edit/{slug:[a-zA-Z\d\s-_\-]+}', 'Sihae\PostController:update');
    $app->get('/post/delete/{slug:[a-zA-Z\d\s-_\-]+}', 'Sihae\PostController:delete');
    $app->get('/post/{slug:[a-zA-Z\d\s-_\-]+}', 'Sihae\PostController:show');

    $app->get('/login', 'Sihae\LoginController:showForm');
    $app->post('/login', 'Sihae\LoginController:login');
    $app->get('/logout', 'Sihae\LoginController:logout');

    $app->get('/register', 'Sihae\RegistrationController:showForm');
    $app->post('/register', 'Sihae\RegistrationController:register');
};
