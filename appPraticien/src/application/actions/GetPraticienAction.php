<?php

namespace toubeelib\application\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
//exceptions
use Slim\Exception\HttpInternalServerErrorException;
//routing
use Slim\Routing\RouteContext;
//renderer
use toubeelib\application\renderer\JsonRenderer;
//services
use toubeelib\core\services\praticien\ServicePraticienInterface;
use toubeelib\core\services\praticien\ServicePraticienInternalServerError;

class GetPraticienAction extends AbstractAction
{
    private ServicePraticienInterface $servicePraticienInterface;

    public function __construct(ServicePraticienInterface $servicePraticienInterface)
    {
        $this->servicePraticienInterface = $servicePraticienInterface;
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {
        try{
            $search = $rq->getQueryParams()['search'] ?? null;
            if ($search) {
                $praticiens = $this->servicePraticienInterface->searchPraticiens($search);
            } else {
                $praticiens = $this->servicePraticienInterface->getAllPraticiens();
            }
            $routeContext = RouteContext::fromRequest($rq);
            $routeParser = $routeContext->getRouteParser();
            $urlPraticiens = $routeParser->urlFor('praticiens');
            $praticiens = array_map(function($praticien) use ($routeParser) {
                $urlPraticien = $routeParser->urlFor('praticienId', ['id' => $praticien->id]);
                return [
                    "id" => $praticien->id,
                    "nom" => $praticien->nom,
                    "prenom" => $praticien->prenom,
                    "specialite_label" => $praticien->specialite_label,
                    "adresse" => $praticien->adresse,
                    "tel" => $praticien->tel,
                    "links" => [
                        "self" => ['href' => $urlPraticien]
                    ]
                ];
            }, $praticiens);

            $result = [
                "type" => "collection",
                "locale" => "fr-FR",
                "praticiens" => $praticiens,
                "links" => [
                    "self" => ["href" => $urlPraticiens],

                ]
            ];
            return JsonRenderer::render($rs, 200, $result);
        } catch (ServicePraticienInternalServerError $e) {
            throw new HttpInternalServerErrorException($rq, $e->getMessage());
        }
    }
}