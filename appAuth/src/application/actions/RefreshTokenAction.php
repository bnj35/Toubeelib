<?php
namespace toubeelib\application\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpUnauthorizedException;
use toubeelib\application\provider\AuthProviderInterface;
use toubeelib\application\renderer\JsonRenderer;
use toubeelib\core\services\auth\AuthentificationServiceInternalServerErrorException;

class RefreshTokenAction extends AbstractAction
{

    private AuthProviderInterface $authProviderInterface;

    public function __construct(AuthProviderInterface $authProviderInterface)
    {
        $this->authProviderInterface = $authProviderInterface;
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {
        try {
            $token = $rq->getHeader('Authorization')[0] ?? null;
            
            if ($token === null) {
                
                return $rs->withStatus(401);
            }

            $token = str_replace('Bearer ', '', $token);
            $authDTO = $this->authProviderInterface->refresh($token);
            $res = [
                'Token:   '.$authDTO->token,
                'Refresh token:   '.$authDTO->token_refresh
            ];
            return JsonRenderer::render($rs, 201, $res);
        } catch (AuthentificationServiceInternalServerErrorException $e) {
            throw new HttpUnauthorizedException($rq, $e->getMessage());
                }
    }
}