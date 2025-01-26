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
//AMQP
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class CreateRdvAction extends AbstractAction{

    private ServiceRdvInterface $RdvServiceInterface;

    public function __construct(ServiceRdvInterface $RdvService){
        $this->RdvServiceInterface = $RdvService;
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

            $rdvArray = [
                "id" => $rdv->id,
                "date" => $rdv->date->format('Y-m-d H:i:s'),
                "duree" => $rdv->duree,
                "statut" => $rdv->statut
            ];

            $response = [
                "type" => "resource",
                "locale" => "fr-FR",
                "rdv" => $rdvArray,
                "links" => [
                    "self" => ['href' => $urlRDV],
                    "praticien" => ['href' => $urlPraticien],
                    "patient" => ['href' => $urlPatient]
                ]
            ];
            //message queue

            $connection = new AMQPStreamConnection('rabbitmq', 5672, 'admin', 'admin');
$channel = $connection->channel();
$channel->queue_declare('notification_queue', false, false, false, false);

$messageData = [
    'event' => 'CREATE',
    'recipient' => [
        'praticienId' => $rdv->praticienId,
        'patientId' => $rdv->patientId
    ],
    'details' => $rdvArray
];

$msg = new AMQPMessage(json_encode($messageData));
$channel->basic_publish($msg, '', 'notification_queue');

$channel->close();
$connection->close();

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