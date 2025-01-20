<?php

use Psr\Container\ContainerInterface;
use toubeelib\core\repositoryInterfaces\PraticienRepositoryInterface;
use toubeelib\infrastructure\db\PDOPraticienRepository;
use toubeelib\core\services\praticien\ServicePraticien;
use toubeelib\core\services\praticien\ServicePraticienInterface;
use toubeelib\application\actions\CreatePraticienAction;
use toubeelib\application\actions\GetPraticienAction;
use toubeelib\application\actions\GetPraticienByIdAction;
use toubeelib\core\services\auth\AuthProviderInterface;
use toubeelib\application\Provider\JWTAuthProvider;
use toubeelib\application\Provider\JWTManager;

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

    //PDO
    'auth.pdo' => function (ContainerInterface $c) {
        $data = parse_ini_file($c->get('auth.ini'));
        $authPdo = new PDO('pgsql:host=' . $data['host'] . ';dbname=' . $data['dbname'], $data['username'], $data['password']);
        $authPdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        return $authPdo;
    },

    'praticien.pdo' => function (ContainerInterface $c) {
        $data = parse_ini_file($c->get('praticien.ini'));
        $praticienPdo = new PDO('pgsql:host='.$data['host'].';dbname='.$data['dbname'], $data['username'], $data['password']);
        $praticienPdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        return $praticienPdo;
    },


    //Repositories

    PraticienRepositoryInterface::class => function (ContainerInterface $c) {
        return new PDOPraticienRepository($c->get('praticien.pdo'));
    },
    
    
    //Services

    // ServiceAuthorizationPraticienInterface::class => function (ContainerInterface $c) {
    //     return new ServiceAuthorizationPraticien();
    // },
    ServicePraticienInterface::class => function (ContainerInterface $c) {
        return new ServicePraticien($c->get(PraticienRepositoryInterface::class));
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
    GetPraticienByIdAction::class => function (ContainerInterface $c) {
        return new GetPraticienByIdAction($c->get(ServicePraticienInterface::class));
    },

    CreatePraticienAction::class => function (ContainerInterface $c) {
        return new CreatePraticienAction($c->get(ServicePraticienInterface::class));
    },

    GetPraticienAction::class => function (ContainerInterface $c) {
        return new GetPraticienAction($c->get(ServicePraticienInterface::class));
    },
];
