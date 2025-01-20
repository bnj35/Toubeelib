<?php

namespace toubeelib\application\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
//exceptions
use Slim\Exception\HttpInternalServerErrorException;
use Slim\Exception\HttpNotFoundException;
//routing
use Slim\Routing\RouteContext;
//renderer
use toubeelib\application\renderer\JsonRenderer;
//services
use toubeelib\core\services\patient\ServicePatientInterface;
use toubeelib\core\services\patient\ServicePatientInternalServerError;
use toubeelib\core\services\patient\ServicePatientInvalidDataException;

class GetPatientByIdAction extends AbstractAction
{
    private ServicePatientInterface $servicePatient;

    public function __construct(ServicePatientInterface $servicePatient)
    {
        $this->servicePatient = $servicePatient;
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {
        try{
            $patientId = $args['id'];
            $patient = $this->servicePatient->getPatientById($patientId);
            $routeContext = RouteContext::fromRequest($rq);
            $routeParser = $routeContext->getRouteParser();
            $urlPatient = $routeParser->urlFor('patientId', ['id' => $patient->id]);
            $response = [
                "type" => "resource",
                "locale" => "fr-FR",
                "patient" => $patient,
                "links" => [
                    "self" => ['href' => $urlPatient]
                ]
            ];
            return JsonRenderer::render($rs, 200, $response);
        } catch (ServicePatientInvalidDataException $e) {
            throw new HttpNotFoundException($rq, $e->getMessage());
        } catch (ServicePatientInternalServerError $e) {
            throw new HttpInternalServerErrorException($rq, $e->getMessage());
        }
    }
}