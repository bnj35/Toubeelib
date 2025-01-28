<?php
namespace toubeelib\application\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpBadRequestException;
use toubeelib\application\provider\AuthProviderInterface;
use toubeelib\application\renderer\JsonRenderer;
use toubeelib\core\dto\auth\CredentialsDTO;
use toubeelib\core\services\auth\AuthentificationServiceInternalServerErrorException;
use toubeelib\core\repositoryInterfaces\RepositoryEntityNotFoundException;

class RegisterAction extends AbstractAction
{
    private AuthProviderInterface $authProviderInterface;

    public function __construct(AuthProviderInterface $authProviderInterface)
    {
        $this->authProviderInterface = $authProviderInterface;
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {
        try {
            $data = $rq->getParsedBody();
            if (empty($data['email']) || empty($data['password'])) {
                throw new HttpBadRequestException($rq, "email and password are required");
            }

            $credentialsDTO = new CredentialsDTO($data['email'], $data['password']);
            $this->authProviderInterface->register($credentialsDTO);

            return JsonRenderer::render($rs, 201, ['message' => 'User registered successfully']);
        } catch (AuthentificationServiceInternalServerErrorException $e) {
            throw new HttpUnauthorizedException($rq, $e->getMessage());
        } catch (RepositoryEntityNotFoundException $e) {
            throw new HttpBadRequestException($rq, "User not found");
        }
    }
}