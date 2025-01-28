<?php
declare(strict_types=1);

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\App;

return function( \Slim\App $app):\Slim\App {

    // $app->get('/', \toubeelib\application\actions\HomeAction::class);

    return $app;
};