<?php

use Psr\Container\ContainerInterface;
use toubeelib\core\repositories\AuthRepositoryInterface;
use toubeelib\core\services\praticien\ServicePraticien;
use toubeelib\core\services\praticien\ServicePraticienInterface;
use toubeelib\core\services\rdv\ServiceRdv;
use toubeelib\core\services\rdv\ServiceRdvInterface;
use toubeelib\core\services\patient\PatientService; 
use toubeelib\core\services\patient\ServicePatientInterface; 
use toubeelib\core\repositoryInterfaces\PraticienRepositoryInterface;
use toubeelib\core\repositoryInterfaces\RdvRepositoryInterface;
use toubeelib\core\repositoryInterfaces\PatientRepositoryInterface;
use toubeelib\infrastructure\repositories\ArrayPraticienRepository;
use toubeelib\infrastructure\repositories\ArrayRdvRepository;
use toubeelib\infrastructure\repositories\PatientRepository;
use toubeelib\application\actions\CreatePraticienAction;
use toubeelib\application\actions\GetPraticienAction;
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
use toubeelib\core\services\praticien\PraticienInfoServiceInterface;
use toubeelib\core\services\praticien\PraticienInfoService;
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

    //PDO
    'auth.pdo' => function (ContainerInterface $c) {
        $data = parse_ini_file($c->get('auth.ini'));
        $authPdo = new PDO('pgsql:host=' . $data['host'] . ';dbname=' . $data['dbname'], $data['username'], $data['password']);
        $authPdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        return $authPdo;
    },
    'rdv.pdo' => function (ContainerInterface $c) {
        $data = parse_ini_file($c->get('rdv.ini'));
        $rdvPdo = new PDO('pgsql:host='.$data['host'].';dbname='.$data['dbname'], $data['username'], $data['password']);
        $rdvPdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        return $rdvPdo;
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
    
    AuthRepositoryInterface::class => function (ContainerInterface $c) {
        return new PDOAuthRepository($c->get('auth.pdo'));
    },
    RdvRepositoryInterface::class => function (ContainerInterface $c) {
        return new PDORdvRepository($c->get('rdv.pdo'));
    },
    PatientRepositoryInterface::class => function (ContainerInterface $c) {
        return new PDOPatientRepository($c->get('patient.pdo'));
    },

    
    //Services
    PraticienInfoServiceInterface::class => function (ContainerInterface $c) {
        return new PraticienInfoService($c->get('praticien.client'));
    },

    ServiceAuthentificationInterface::class => function (ContainerInterface $c) {
        return new ServiceAuthentification(
            $c->get(AuthRepositoryInterface::class),
        );
    },
    ServiceAuthorizationRdvInterface::class => function (ContainerInterface $c) {
        return new ServiceAuthorizationRdv(
            $c->get(RdvRepositoryInterface::class)
        );
    },
    ServiceAuthorizationPatientInterface::class => function (ContainerInterface $c) {
        return new ServiceAuthorizationPatient();
    },
    ServiceAuthorizationPraticienInterface::class => function (ContainerInterface $c) {
        return new ServiceAuthorizationPraticien( $c->get('praticien.client'));
    },
    ServiceRdvInterface::class => function (ContainerInterface $c) {
        return new ServiceRdv(
            $c->get(PraticienInfoServiceInterface::class),
            $c->get(RdvRepositoryInterface::class),
            $c->get(PatientRepositoryInterface::class),
            $c->get('prog.logger')
        );
    },

    ServicePatientInterface::class => function (ContainerInterface $c) {
        return new PatientService(
            $c->get(PatientRepositoryInterface::class),
            $c->get(RdvRepositoryInterface::class)
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
            $c->get(PraticienInfoServiceInterface::class),
            $c->get(ServicePatientInterface::class)
        );
    },

    GetPlanningByPraticienAction::class => function (ContainerInterface $c) {
        return new GetPlanningByPraticienAction($c->get(ServiceRdvInterface::class));
    },
];