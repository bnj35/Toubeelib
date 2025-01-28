<?php

use Psr\Container\ContainerInterface;
use toubeelib\application\actions\GatewayPraticienAction;
use toubeelib\application\actions\GatewayRdvAction;
use toubeelib\application\actions\GatewayAuthAction;
use toubeelib\core\services\auth\ServiceAuthentificationInterface;
use GuzzleHttp\Client;
use toubeelib\application\middleware\AuthMiddleware;

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

  // Middleware
    AuthMiddleware::class => function (ContainerInterface $c) {
    return new AuthMiddleware($c->get('auth.client'));
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
