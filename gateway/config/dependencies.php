<?php

use Psr\Container\ContainerInterface;
use toubeelib\core\repositories\AuthRepositoryInterface;
use toubeelib\core\services\praticien\ServicePraticien;
use toubeelib\core\services\praticien\ServicePraticienInterface;
use toubeelib\core\services\rdv\ServiceRdv;
use toubeelib\core\services\rdv\ServiceRdvInterface;
use toubeelib\core\services\patient\ServicePatient; 
use toubeelib\core\services\patient\ServicePatientInterface; 
use toubeelib\core\repositoryInterfaces\PraticienRepositoryInterface;
use toubeelib\core\repositoryInterfaces\RdvRepositoryInterface;
use toubeelib\core\repositoryInterfaces\PatientRepositoryInterface;
use toubeelib\infrastructure\repositories\ArrayPraticienRepository;
use toubeelib\infrastructure\repositories\ArrayRdvRepository;
use toubeelib\infrastructure\repositories\PatientRepository;
use toubeelib\application\actions\CreatePraticienAction;
use toubeelib\application\actions\GetPraticienByIdAction;
use toubeelib\application\actions\GetRdvAction;
use toubeelib\application\actions\CreateRdvAction;
use toubeelib\application\actions\GetPlanningByPraticienAction;
use toubeelib\application\actions\SignInAction;
use toubeelib\infrastructure\db\PDOAuthRepository;
use toubeelib\infrastructure\db\PDOPraticienRepository;
use toubeelib\infrastructure\db\PDORdvRepository;
use toubeelib\infrastructure\db\PDOPatientRepository;
use toubeelib\core\services\auth\ServiceAuthentification;
use toubeelib\core\services\auth\ServiceAuthentificationInterface;
use toubeelib\core\services\auth\ServiceAuthorizationRdv;
use toubeelib\core\services\auth\ServiceAuthorizationRdvInterface;
use toubeelib\core\services\auth\ServiceAuthorizationPatient;
use toubeelib\core\services\auth\ServiceAuthorizationPatientInterface;
use toubeelib\core\services\auth\ServiceAuthorizationPraticien;
use toubeelib\core\services\auth\ServiceAuthorizationPraticienInterface;
use toubeelib\core\services\auth\AuthProviderInterface;
use toubeelib\application\Provider\JWTAuthProvider; 
use toubeelib\application\Provider\JWTManager;
use GuzzleHttp\Client;
use toubeelib\application\actions\GatewayPraticienAction;
use toubeelib\application\actions\GatewayRdvAction;
use toubeelib\application\actions\GatewayAuthAction;
use toubeelib\infrastructure\services\PraticienInfoService;

return [

    //log 
    'log.prog.level' => \Monolog\Level::Debug,
    'log.prog.name' => 'toubeelib.program.log',
    'log.prog.file' => __DIR__ . '/log/toubeelib.program.error.log',
    'prog.logger' => function (ContainerInterface $c) {
        $logger = new \Monolog\Logger($c->get('log.prog.name'));
        $logger->pushHandler(
            new \Monolog\Handler\StreamHandler(
                $c->get('log.prog.file'),
                $c->get('log.prog.level')));
        return $logger;
    },


    // Guzzle clients for microservices
    'praticien.client' => function (ContainerInterface $c) {
        return new Client(['base_uri' => 'http://api.praticien.toubeelib/']);
    },
    'rdv.client' => function (ContainerInterface $c) {
        return new Client(['base_uri' => 'http://api.rdv.toubeelib/']);
    },
    'auth.client' => function (ContainerInterface $c) {
        return new Client(['base_uri' => 'http://api.auth.toubeelib/']);
    },
    'app.client' => function (ContainerInterface $c) {
        return new Client(['base_uri' => 'http://api.app.toubeelib/']);
    },

    // Providers
    AuthProviderInterface::class => function (ContainerInterface $c) {
        return new JWTAuthProvider(
            $c->get(ServiceAuthentificationInterface::class),
            new JWTManager
        );
    },
    //Actions

    //praticiens

    GatewayPraticienAction::class => function(ContainerInterface $c) {
        return new GatewayPraticienAction($c->get('praticien.client'));
    },
    
    //rdvs

    GatewayRdvAction::class => function(ContainerInterface $c) {
        return new GatewayRdvAction(
            $c->get('rdv.client'),
        );
    },

    //auth

    GatewayAuthAction::class => function(ContainerInterface $c) {
        return new GatewayAuthAction($c->get('auth.client'));
    },


];
