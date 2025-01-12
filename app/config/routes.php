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

    //micro-service rdv
    
    $app->get('patients/{id}', \toubeelib\application\actions\GetPatientByIdAction::class)
    ->setName('patientId');

    $app->get('/rdvs/{id}', \toubeelib\application\actions\GetRdvAction::class)
    ->setName('rdvId');

    $app->post('/rdvs', \toubeelib\application\actions\CreateRdvAction::class)
    ->setName('create_rdv');

//c'est la seule qui ne marche pas pour l'instant
    $app->get('/praticiens/{id}/agenda', \toubeelib\application\actions\GetPlanningByPraticienAction::class)
    ->setName('get_agenda_by_praticien');

    //micro-service authentification

    $app->get('/signin', \toubeelib\application\actions\SignInAction::class)
    ->setName('signin');
    

    return $app;
};