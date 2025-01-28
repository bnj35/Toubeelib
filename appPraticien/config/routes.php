<?php
declare(strict_types=1);

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\App;

return function( \Slim\App $app):\Slim\App {

    $app->get('/', \toubeelib\application\actions\HomeAction::class);

    //micro-service praticien

    $app->get('/praticiens/{id}', \toubeelib\application\actions\GetPraticienByIdAction::class)
    ->setName('praticienId');

    $app->get('/praticiens', \toubeelib\application\actions\GetPraticienAction::class)
    ->setName('praticiens');

    $app->post('/praticiens', \toubeelib\application\actions\CreatePraticienAction::class)
    ->setName('create_praticien');

    return $app;
};