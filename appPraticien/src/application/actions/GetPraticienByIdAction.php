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
use toubeelib\core\services\praticien\ServicePraticienInterface;
use toubeelib\core\services\praticien\ServicePraticienInternalServerError;
use toubeelib\core\services\praticien\ServicePraticienInvalidDataException;

class GetPraticienByIdAction extends AbstractAction
{
    private ServicePraticienInterface $servicePraticien;

    public function __construct(ServicePraticienInterface $servicePraticien)
    {
        $this->servicePraticien = $servicePraticien;
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {
        try{
            $praticienId = $args['id'];
            $praticien = $this->servicePraticien->getPraticienById($praticienId);
            $routeContext = RouteContext::fromRequest($rq);
            $routeParser = $routeContext->getRouteParser();
            $urlPraticien = $routeParser->urlFor('praticienId', ['id' => $praticien->id]);
            $praticienArray = [
                "id" => $praticien->id,
                "nom" => $praticien->nom,
                "prenom" => $praticien->prenom,
                "specialite_label" => $praticien->specialite_label,
                "adresse" => $praticien->adresse,
                "tel" => $praticien->tel
            ];
            $response = [
                "type" => "resource",
                "locale" => "fr-FR",
                "praticien" => $praticienArray,
                "links" => [
                    "self" => ['href' => $urlPraticien]
                ]
            ];
            return JsonRenderer::render($rs, 200, $response);
        } catch (ServicePraticienInvalidDataException $e) {
            throw new HttpNotFoundException($rq, $e->getMessage());
        } catch (ServicePraticienInternalServerError $e) {
            throw new HttpInternalServerErrorException($rq, $e->getMessage());
        }
    }
}