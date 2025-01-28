<?php
namespace toubeelib\application\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
//validation
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator;
//exceptions
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpInternalServerErrorException;
use Slim\Exception\HttpNotFoundException;
use Slim\Routing\RouteContext;
//renderer
use toubeelib\application\renderer\JsonRenderer;
//DTO
use toubeelib\core\dto\rdv\CreateRdvDTO;
//services
use toubeelib\core\services\rdv\RdvInternalServerError;
use toubeelib\core\services\rdv\RdvNotFoundException;
use toubeelib\core\services\rdv\RdvPraticienNotFoundException;
use toubeelib\core\services\rdv\ServiceRdvInterface;
use toubeelib\core\services\praticien\PraticienInfoServiceInterface;
use toubeelib\core\services\patient\PatientServiceInterface;
//AMQP
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class CreateRdvAction extends AbstractAction{

    private ServiceRdvInterface $RdvServiceInterface;
    private PraticienInfoServiceInterface $praticienInfoService;
    private PatientServiceInterface $patientService;

    public function __construct(ServiceRdvInterface $RdvService, PraticienInfoServiceInterface $praticienInfoService, PatientServiceInterface $patientService){
        $this->RdvServiceInterface = $RdvService;
        $this->praticienInfoService = $praticienInfoService;
        $this->patientService = $patientService;
        
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args) : ResponseInterface{
        try{
            $routeContext = RouteContext::fromRequest($rq);
            $routeParser = $routeContext->getRouteParser();

            $data = $rq->getParsedBody();
            $placeInputValidator = Validator::key('date', Validator::stringType()->notEmpty())
            ->key('duree', Validator::intVal()->notEmpty())
            ->key('praticienId', Validator::stringType()->notEmpty())
            ->key('patientId', Validator::stringType()->notEmpty())
            ->key('specialiteId', Validator::stringType()->notEmpty());
            try{
                $placeInputValidator->assert($data);
            } catch (NestedValidationException $e) {
                throw new HttpBadRequestException($rq, $e->getFullMessage());
            }
            if(filter_var($data["date"],FILTER_SANITIZE_FULL_SPECIAL_CHARS) !== $data["date"] ){
                throw new HttpBadRequestException($rq, "Bad data format date");
            }
            if(filter_var($data["praticienId"],FILTER_SANITIZE_FULL_SPECIAL_CHARS) !== $data["praticienId"]){
                throw new HttpBadRequestException($rq, "Bad data format praticienId");
            }
            if(filter_var($data["patientId"],FILTER_SANITIZE_FULL_SPECIAL_CHARS) !== $data["patientId"]){
                throw new HttpBadRequestException($rq, "Bad data format patientId");
            }
            if(filter_var($data["specialiteId"],FILTER_SANITIZE_FULL_SPECIAL_CHARS) !== $data["specialiteId"]){
                throw new HttpBadRequestException($rq, "Bad data format specialiteId");
            }

            $dto = new CreateRdvDTO($data["date"], $data["duree"], $data["praticienId"], $data["patientId"], $data["specialiteId"]);

            $rdv = $this->RdvServiceInterface->createRdv($dto);

$urlPraticien = $routeParser->urlFor('praticienId', ['id' => $rdv->praticienId]);
$urlPatient = $routeParser->urlFor('patientId', ['id' => $rdv->patientId]);
$urlRDV = $routeParser->urlFor('rdvId', ['id' => $rdv->id]);

$praticien = $this->praticienInfoService->getPraticienById($rdv->praticienId);
$patient = $this->patientService->getPatientById($rdv->patientId);

$response = [
    "type" => "resource",
    "locale" => "fr-FR",
    "rdv" => $rdv,
    "links" => [
        "self" => ['href' => $urlRDV],
        "praticien" => ['href' => $urlPraticien],
        "patient" => ['href' => $urlPatient]
    ]
];

//message queue
if ($response != null) {
$connection = new AMQPStreamConnection('rabbitmq', 5672, 'admin', 'admin');
$channel = $connection->channel();
$channel->exchange_declare('notification_exchange', 'direct', false, true, false);
$channel->queue_declare('notification_queue', false, true, false, false, false);
$channel->queue_bind('notification_queue', 'notification_exchange');

$messageData = [
    'event' => 'CREATE',
    'recipient' => [
        'praticienId' => $rdv->praticienId,
        'patientId' => $rdv->patientId,
        'praticienMail' => $praticien['email'],
        'patientMail' => $patient->email
    ],
    'details' => " date: ".$rdv->date->format('Y-m-d H:i:s')." durée: ".$rdv->duree." specialite: ".$rdv->speciality
];

$msg = new AMQPMessage(json_encode($messageData), ['delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT]);
$channel->basic_publish($msg, 'notification_exchange');

$channel->close();
$connection->close();
};

return JsonRenderer::render($rs, 201, $response);
        }
        catch( RdvPraticienNotFoundException $e){
            throw new HttpNotFoundException($rq, $e->getMessage());
        }
        catch( RdvInternalServerError $e){
            throw new HttpInternalServerErrorException($rq, $e->getMessage());
        }
    }
}