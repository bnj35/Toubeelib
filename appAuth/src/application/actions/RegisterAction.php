<?php
namespace toubeelib\application\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpBadRequestException;
use toubeelib\application\provider\auth\AuthProviderInterface;
use toubeelib\application\renderer\JsonRenderer;
use toubeelib\core\services\auth\AuthentificationServiceInternalServerErrorException;

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
            if (empty($data['username']) || empty($data['password'])) {
                throw new HttpBadRequestException($rq, "Username and password are required");
            }

            $username = $data['username'];
            $password = $data['password'];

            $authDTO = $this->authProviderInterface->register($username, $password);
            $res = [
                'token' => $authDTO->token,
                'refreshToken' => $authDTO->refreshToken
            ];
            return JsonRenderer::render($rs, 201, $res);
        } catch (AuthentificationServiceInternalServerErrorException $e) {
            throw new HttpUnauthorizedException($rq, $e->getMessage());
        }
    }
}