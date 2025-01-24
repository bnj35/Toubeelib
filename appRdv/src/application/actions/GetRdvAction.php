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
use toubeelib\core\services\praticien\PraticienInfoServiceInterface;


class GetRdvAction extends AbstractAction
{
    private ServiceRdvInterface $RdvServiceInterface;
    private PraticienInfoServiceInterface $praticienInfoService;

    public function __construct(ServiceRdvInterface $RdvService, PraticienInfoServiceInterface $praticienInfoService)
    {
        $this->RdvServiceInterface = $RdvService;
        $this->praticienInfoService = $praticienInfoService;
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args):ResponseInterface
    {
        try{
            
            $rdvId = $args['id'];
            $routeContext = RouteContext::fromRequest($rq);
            $routeParser = $routeContext->getRouteParser();
            $rdv = $this->RdvServiceInterface->getRdvById($rdvId);
            $praticien = $this->praticienInfoService->getPraticienById($rdv->praticienId);
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
                return JsonRenderer::render($rs, 200, $response);
        }catch (RdvNotFoundException $e){
            throw new HttpNotFoundException($rq, $e->getMessage());
        }catch (RdvInternalServerErrorException $e){
            throw new HttpInternalServerErrorException($rq, $e->getMessage());
        }
    }

}