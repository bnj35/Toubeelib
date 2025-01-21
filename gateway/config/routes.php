<?php
declare(strict_types=1);

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\App;
use GuzzleHttp\Client;

return function(App $app):App {

    $app->get('/', \toubeelib\application\actions\HomeAction::class);

    //micro-service praticien

    $app->get('/praticiens/{id}', \toubeelib\application\actions\GatewayPraticienAction::class)
    ->setName('praticienId');

    $app->get('/praticiens', \toubeelib\application\actions\GatewayPraticienAction::class)
    ->setName('praticiens');

    $app->post('/praticiens', \toubeelib\application\actions\GatewayPraticienAction::class)
    ->setName('create_praticien');

    //micro-service rdv
    
    $app->get('patients/{id}', \toubeelib\application\actions\GatewayRdvAction::class)
    ->setName('patientId');

    $app->get('/rdvs/{id}', \toubeelib\application\actions\GatewayRdvAction::class)
    ->setName('rdvId');

    $app->post('/rdvs', \toubeelib\application\actions\GatewayRdvAction::class)
    ->setName('create_rdv');

    $app->get('/praticiens/{praticien_id}/planning', \toubeelib\application\actions\GatewayRdvAction::class)
    ->setName('praticien_planning');

    //micro-service authentification

    $app->post('/signin', \toubeelib\application\actions\GatewayAuthAction::class)
    ->setName('signin');

    $app->get('/refresh', \toubeelib\application\actions\GatewayAuthAction::class)
    ->setName('refresh');

    $app->post('/register', \toubeelib\application\actions\GatewayAuthAction::class)
    ->setName('register');

    

    return $app;
};