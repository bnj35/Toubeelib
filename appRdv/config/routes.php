<?php
declare(strict_types=1);

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\App;

return function( \Slim\App $app):\Slim\App {

    $app->get('/', \toubeelib\application\actions\HomeAction::class);

    //micro-service rdv
    
    $app->get('patients/{id}', \toubeelib\application\actions\GetPatientByIdAction::class)
    ->setName('patientId');

    $app->get('/rdvs/{id}', \toubeelib\application\actions\GetRdvAction::class)
    ->setName('rdvId');

    $app->post('/rdvs', \toubeelib\application\actions\CreateRdvAction::class)
    ->setName('create_rdv');

    $app->get('/praticiens/{praticien_id}/planning', \toubeelib\application\actions\GetPlanningByPraticienAction::class)
    ->setName('praticien_planning');

    //micro-service praticien

    $app->get('/praticiens/{id}', \toubeelib\application\actions\GetPraticienByIdAction::class)
    ->setName('praticienId');


    return $app;
};