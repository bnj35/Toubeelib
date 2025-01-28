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
//routing
use Slim\Routing\RouteContext;
//renderer
use toubeelib\application\renderer\JsonRenderer;
//DTO
use toubeelib\core\dto\praticien\InputPraticienDTO;
//services
use toubeelib\core\services\praticien\ServicePraticienInterface;
use toubeelib\core\services\praticien\ServicePraticienInternalServerError;
use toubeelib\core\services\praticien\ServicePraticienInvalidDataException;

class CreatePraticienAction extends AbstractAction
{
    private ServicePraticienInterface $servicePraticien;

    public function __construct(ServicePraticienInterface $servicePraticien)
    {
        $this->servicePraticien = $servicePraticien;
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {
        try{
            $routeContext = RouteContext::fromRequest($rq);
            $routeParser = $routeContext->getRouteParser();



            $data = $rq->getParsedBody();
            $placeInputValidator = Validator::key('prenom', Validator::stringType()->notEmpty())
                ->key('nom', Validator::stringType()->notEmpty())
                ->key('adresse', Validator::stringType()->notEmpty())
                ->key('telephone', Validator::stringType()->notEmpty())
                ->key('specialite_id', Validator::stringType()->notEmpty());
            try{
                $placeInputValidator->assert($data);
            } catch (NestedValidationException $e) {
                throw new HttpBadRequestException($rq, $e->getFullMessage());
            }
            if(filter_var($data["prenom"],FILTER_SANITIZE_FULL_SPECIAL_CHARS) !== $data["prenom"] ){
                throw new HttpBadRequestException($rq, "Bad data format prenom");
            }
            if(filter_var($data["nom"],FILTER_SANITIZE_FULL_SPECIAL_CHARS) !== $data["nom"]){
                throw new HttpBadRequestException($rq, "Bad data format nom");
            }
            if(filter_var($data["telephone"],FILTER_SANITIZE_FULL_SPECIAL_CHARS) !== $data["telephone"]){
                throw new HttpBadRequestException($rq, "Bad data format telephone");
            }
            if(filter_var($data["specialite_id"],FILTER_SANITIZE_FULL_SPECIAL_CHARS) !== $data["specialite_id"]){
                throw new HttpBadRequestException($rq, "Bad data format specialite_id");
            }
            if(filter_var($data["adresse"],FILTER_SANITIZE_FULL_SPECIAL_CHARS) !== $data["adresse"]){
                throw new HttpBadRequestException($rq, "Bad data format adresse");
            }
            

            $dto = new InputPraticienDTO($data["prenom"], $data["nom"], $data["adresse"], $data["telephone"], $data["specialite_id"]);
            $praticien = $this->servicePraticien->createPraticien($dto);
            $urlPraticien = $routeParser->urlFor('praticienId', ['id' => $praticien->id]);
            $response = [
                "type" => "resource",
                "locale" => "fr-FR",
                "praticien" => $praticien,
                "links" => [
                    "self" => ['href' => $urlPraticien],
                ]
            ];

            return JsonRenderer::render($rs, 201, $response);
        }catch (ServicePraticienInvalidDataException $e) {
            throw new HttpBadRequestException($rq, $e->getMessage());
        } catch (ServicePraticienInternalServerError $e) {
            throw new HttpInternalServerErrorException($rq, $e->getMessage());
        }



    }
}