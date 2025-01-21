<?php

use Psr\Container\ContainerInterface;
use toubeelib\core\repositories\AuthRepositoryInterface;
use toubeelib\infrastructure\db\PDOAuthRepository;
use toubeelib\core\services\auth\ServiceAuthentification;
use toubeelib\core\services\auth\ServiceAuthentificationInterface;
use toubeelib\application\provider\AuthProviderInterface;
use toubeelib\application\provider\JWTAuthProvider;
use toubeelib\application\provider\JWTManager;
use toubeelib\application\actions\SignInAction;
use toubeelib\application\actions\RefreshTokenAction;
use toubeelib\application\actions\RegisterAction;

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

    //Repositories
    AuthRepositoryInterface::class => function (ContainerInterface $c) {
        return new PDOAuthRepository($c->get('auth.pdo'));
    },
    
    //Services
    ServiceAuthentificationInterface::class => function (ContainerInterface $c) {
        return new ServiceAuthentification(
            $c->get(AuthRepositoryInterface::class),
        );
    },

    // Providers
    AuthProviderInterface::class => function (ContainerInterface $c) {
        return new JWTAuthProvider(
            $c->get(ServiceAuthentificationInterface::class),
            new JWTManager
        );
    },

    //Actions
    SignInAction::class => function (ContainerInterface $c) {
        return new SignInAction(
            $c->get(AuthProviderInterface::class),
            $c->get(ServiceAuthentificationInterface::class)
        );
    },

    RefreshTokenAction::class => function(ContainerInterface $c) {
        return new RefreshTokenAction(
            $c->get(AuthProviderInterface::class),
            $c->get(ServiceAuthentificationInterface::class)
        );
    },

    RegisterAction::class => function(ContainerInterface $c) {
        return new RegisterAction(
            $c->get(AuthProviderInterface::class),
            $c->get(ServiceAuthentificationInterface::class)
        );
    },
];
