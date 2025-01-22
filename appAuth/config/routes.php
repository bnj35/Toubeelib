<?php
declare(strict_types=1);

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\App;

return function( \Slim\App $app):\Slim\App {

    $app->get('/', \toubeelib\application\actions\HomeAction::class);

    //micro-service authentification

    $app->post('/signin', \toubeelib\application\actions\SignInAction::class)
    ->setName('signin');

    $app->get('/refresh', \toubeelib\application\actions\RefreshTokenAction::class)
    ->setName('refresh');

    $app->post('/register', \toubeelib\application\actions\RegisterAction::class)
    ->setName('register');
    
    $app->get('/validate', \toubeelib\application\actions\ValidateTokenAction::class)
    ->setName('validate');

    return $app;
};