<?php

namespace toubeelib\application\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
//exceptions
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpInternalServerErrorException;
use Slim\Exception\HttpNotFoundException;
//routing
use Slim\Routing\RouteContext;
//renderer
use toubeelib\application\renderer\JsonRenderer;
//DTO
use toubeelib\core\dto\rdv\DisponibilityPraticienRdvDTO;
//services
use toubeelib\core\services\rdv\RdvBadDataException;
use toubeelib\core\services\rdv\RdvInternalServerError;
use toubeelib\core\services\rdv\ServiceRdvInterface;

class GetPlanningByPraticienAction extends AbstractAction
{

    private ServiceRdvInterface $RdvServiceInterface;

    public function __construct(ServiceRdvInterface $RdvServiceInterface)
    {
        $this->RdvServiceInterface = $RdvServiceInterface;
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {
        try{
            $start = $rq->getQueryParams()['start'] ?? null;
            $end = $rq->getQueryParams()['end'] ?? null;
            $duration = $rq->getQueryParams()['duration'] ?? null;


            if ($start === null || $end === null || $duration === null) {
                throw new HttpNotFoundException($rq, 'Paramètres manquants');
            }

            $start = urldecode($start);
            $end = urldecode($end);

            $praticienId = $args['praticienId'];
            $dto = new PlanningPraticienDTO(
                $praticienId,
                \DateTimeImmutable::createFromFormat('Y-m-d H:i', $start),
                \DateTimeImmutable::createFromFormat('Y-m-d H:i', $end),
                (int)$duration
            );

            $routeContext = RouteContext::fromRequest($rq);
            $routeParser = $routeContext->getRouteParser();
            $urlPraticien = $routeParser->urlFor('praticienId', ['praticien_id' => $praticienId]);
            $urlDispobilites = $routeParser->urlFor('praticien_id_planning', ['praticien_id' => $praticienId]);

            $disponibilities = $this->RdvServiceInterface->getDisponibilityPraticienRdv($dto);
            $result = [
                "type" => "collection",
                "locale" => "fr-FR",
                "disponibilities" => $disponibilities,
                "links" => [
                    "self" => ["href" => $urlDispobilites],
                    "praticien" => ["href" => $urlPraticien]
                ]
            ];


            return JsonRenderer::render($rs, 200, $result);
        }catch (RdvBadDataException $e) {
            throw new HttpBadRequestException($rq, $e->getMessage());
        }catch (RdvInternalServerError $e) {
            throw new HttpInternalServerErrorException($rq, $e->getMessage());
        }

    }
}