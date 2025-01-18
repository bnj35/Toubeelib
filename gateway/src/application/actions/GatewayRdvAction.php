<?php

namespace toubeelib\application\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

use Slim\Exception\HttpInternalServerErrorException;
use Slim\Exception\HttpNotFoundException;
use Slim\Exception\HttpUnauthorizedException;
use Slim\Exception\HttpForbiddenException;

use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\ServerException;

use GuzzleHttp\Client;
use toubeelib\core\services\praticien\PraticienInfoServiceInterface;

class GatewayRdvAction extends GatewayAbstractAction
{
    private Client $client;
    private PraticienInfoServiceInterface $praticienInfoService;

    public function __construct(Client $client, PraticienInfoServiceInterface $praticienInfoService)
    {
        $this->client = $client;
        $this->praticienInfoService = $praticienInfoService;
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {
        $method = $rq->getMethod();
        $path = $rq->getUri()->getPath();
        $options = ['query' => $rq->getQueryParams()];
        if ($method === 'POST' || $method === 'PUT' || $method === 'PATCH') {
            $options['json'] = $rq->getParsedBody();
        }
        $auth = $rq->getHeader('Authorization') ?? null;
        if (!empty($auth)) {
            $options['headers'] = ['Authorization' => $auth];
        }
        try {
            $response = $this->client->request($method, $path, $options);
            $rs->getBody()->write($response->getBody()->getContents());
            return $rs->withStatus($response->getStatusCode());
        } catch (ConnectException | ServerException $e) {
            throw new HttpInternalServerErrorException($rq, " internal server error");
        } catch (ClientException $e) {
            match($e->getCode()) {
                401 => throw new HttpUnauthorizedException($rq, " Unauthorized "),
                403 => throw new HttpForbiddenException($rq, " Forbidden "),
                404 => throw new HttpNotFoundException($rq, " Not found "),
            };
        }
    }
}