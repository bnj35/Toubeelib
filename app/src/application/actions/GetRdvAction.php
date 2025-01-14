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
use toubeelib\core\services\rdv\RdvInternalServerErrorException;
use toubeelib\core\services\rdv\RdvNotFoundException;
use toubeelib\core\services\rdv\ServiceRdvInterface;


class GetRdvAction extends AbstractAction
{
    private ServiceRdvInterface $RdvServiceInterface;

    public function __construct(ServiceRdvInterface $RdvService)
    {
        $this->RdvServiceInterface = $RdvService;
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args):ResponseInterface
    {
        try{
            $rdvId = $args['id'];
            $routeContext = RouteContext::fromRequest($rq);
            $routeParser = $routeContext->getRouteParser();
            $rdv = $this->RdvServiceInterface->getRdvById($rdvId);
            $urlPraticien = $routeParser->urlFor('praticienId', ['id' => $rdv->praticienId]);
            $urlPatient = $routeParser->urlFor('patientId', ['id' => $rdv->patientId]);
            $urlRDV = $routeParser->urlFor('rdvId', ['id' => $rdv->id]);
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
                return JsonRenderer::render($rs, 200, $response);
        }catch (RdvNotFoundException $e){
            throw new HttpNotFoundException($rq, $e->getMessage());
        }catch (RdvInternalServerErrorException $e){
            throw new HttpInternalServerErrorException($rq, $e->getMessage());
        }
    }

}