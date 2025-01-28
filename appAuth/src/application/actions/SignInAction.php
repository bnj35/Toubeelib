<?php
namespace toubeelib\application\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
//exceptions
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpInternalServerErrorException;
use Slim\Exception\HttpNotFoundException;
use Slim\Exception\HttpUnauthorizedException;
//provider
use toubeelib\application\Provider\AuthProviderInterface;
//renderer
use toubeelib\application\renderer\JsonRenderer;
//DTO
use toubeelib\core\dto\auth\CredentialsDTO;
//services
use toubeelib\core\services\auth\AuthentificationServiceBadDataException;
use toubeelib\core\services\auth\AuthentificationServiceInternalServerErrorException;
use toubeelib\core\services\auth\AuthentificationServiceNotFoundException;

class SignInAction extends AbstractAction {
    private AuthProviderInterface $authProviderInterface;

    public function __construct(AuthProviderInterface $authProviderInterface){
        $this->authProviderInterface = $authProviderInterface;
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface{

        $token = $rq->getHeader('Authorization')[0] ?? throw new HttpUnauthorizedException($rq, 'missing Authorization Header');

        if ($token === null) {
            throw new HttpUnauthorizedException($rq, 'Invalid Authorization Header');
        }
        
        $authHeader = explode(' ', $token)[1] ?? null;
        if ($authHeader === null) {
            throw new HttpUnauthorizedException($rq, 'Invalid Authorization Header');
        }

        $decodedAuth = base64_decode($authHeader);
        list($email, $password) = explode(':', $decodedAuth, 2);

        try {
            $authDTO = $this->authProviderInterface->signIn(new CredentialsDTO($email, $password));
            $res = [
                'id' => $authDTO->id,
                'email' => $authDTO->email,
                'role' => $authDTO->role,
                'token' => $authDTO->token,
                'token_refresh' => $authDTO->token_refresh
            ];
            return JsonRenderer::render($rs, 200, $res);
        } catch (AuthentificationServiceNotFoundException $e) {
            throw new HttpNotFoundException($rq, $e->getMessage());
        } catch (AuthentificationServiceBadDataException $e) {
            throw new HttpBadRequestException($rq, $e->getMessage());
        } catch (AuthentificationServiceInternalServerErrorException $e) {
            throw new HttpInternalServerErrorException($rq, $e->getMessage());
        }
    }
}