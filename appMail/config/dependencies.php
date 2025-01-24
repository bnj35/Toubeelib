<?php

use Psr\Container\ContainerInterface;
use toubeelib\core\repositoryInterfaces\PatientRepositoryInterface;
use toubeelib\infrastructure\db\PDOPatientRepository;
use toubeelib\application\actions\GetRdvAction;
use toubeelib\application\actions\CreateRdvAction;
use toubeelib\application\actions\GetPlanningByPraticienAction;
use toubeelib\core\services\auth\ServiceAuthorizationPatientInterface;
use toubeelib\core\services\auth\ServiceAuthorizationPatient;
use toubeelib\core\services\praticien\PraticienInfoServiceInterface;
use toubeelib\core\services\praticien\PraticienInfoService;
use toubeelib\core\services\patient\PatientInfoServiceInterface;
use toubeelib\core\services\patient\PatientInfoService;
use toubeelib\core\service\mail\MailServiceInterface;
use toubeelib\core\service\mail\MailService;
use toubeelib\core\services\auth\AuthProviderInterface;
use toubeelib\application\Provider\JWTAuthProvider;
use toubeelib\application\Provider\JWTManager;
use toubeelib\application\class\ClassMail;
use GuzzleHttp\Client;

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


    'patient.pdo' => function (ContainerInterface $c) {
        $data = parse_ini_file($c->get('patient.ini'));
        $patientPdo = new PDO('pgsql:host='.$data['host'].';dbname='.$data['dbname'], $data['username'], $data['password']);
        $patientPdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        return $patientPdo;
    },

    //client
    'praticien.client' => function (ContainerInterface $c) {
        return new Client(['base_uri' => 'http://api.praticien.toubeelib/']);
    },


    //Repositories
    PatientRepositoryInterface::class => function (ContainerInterface $c) {
        return new PDOPatientRepository($c->get('patient.pdo'));
    },

    
    //Services
    PraticienInfoServiceInterface::class => function (ContainerInterface $c) {
        return new PraticienInfoService($c->get('praticien.client'));
    },

    PatientInfoServiceInterface::class => function (ContainerInterface $c) {
        return new PatientInfoService($c->get('patient.pdo'));
    },

    ServiceAuthorizationPatientInterface::class => function (ContainerInterface $c) {
        return new ServiceAuthorizationPatient();
    },

    MailServiceInterface::class => function (ContainerInterface $c) {
        return new MailService(
            $c->get(PraticienInfoServiceInterface::class),
            $c->get(PatientInfoServiceInterface::class)
        );
    },

    // Providers
    AuthProviderInterface::class => function (ContainerInterface $c) {
        return new JWTAuthProvider(
            $c->get(ServiceAuthentificationInterface::class),
            new JWTManager
        );
    },

    //rdvs
    GetRdvAction::class => function (ContainerInterface $c) {
        return new GetRdvAction(
            $c->get(ServiceRdvInterface::class),
            $c->get(PraticienInfoServiceInterface::class)
        );
    },

    CreateRdvAction::class => function (ContainerInterface $c) {
        return new CreateRdvAction(
            $c->get(ServiceRdvInterface::class),
        );
    },

    GetPlanningByPraticienAction::class => function (ContainerInterface $c) {
        return new GetPlanningByPraticienAction($c->get(ServiceRdvInterface::class));
    },

    ClassMail::class => function (ContainerInterface $c) {
        return new ClassMail(
            $c->get(PraticienInfoServiceInterface::class),
            $c->get(PatientInfoServiceInterface::class),
            $c->get(MailServiceInterface::class)
        );
    },
];