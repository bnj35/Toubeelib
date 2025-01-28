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
use toubeelib\core\dto\rdv\PlanningPraticienDTO;
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

            $praticienId = $args['praticien_id'];

            $start = \DateTimeImmutable::createFromFormat('Y-m-d H:i', $start);
            $end = \DateTimeImmutable::createFromFormat('Y-m-d H:i', $end);

            if ($start === false || $end === false) {
                throw new HttpBadRequestException($rq, 'Invalid date format');
            }

            $dto = new PlanningPraticienDTO(
                $praticienId,
                $start,
                $end,
                (int)$duration
            );

            $routeContext = RouteContext::fromRequest($rq);
            $routeParser = $routeContext->getRouteParser();
            $urlPraticien = $routeParser->urlFor('praticienId', ['id' => $praticienId]);
            $urlPlanning = $routeParser->urlFor('praticien_planning', ['praticien_id' => $praticienId]);

            $Planning = $this->RdvServiceInterface->getPlanningByPraticien($dto);
            $result = [
                "type" => "collection",
                "locale" => "fr-FR",
                "Planning" => $Planning,
                "links" => [
                    "self" => ["href" => $urlPlanning],
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