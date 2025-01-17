<?php
namespace toubeelib\application\actions;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

use toubeelib\application\renderers\JsonRenderer;
use toubeelib\application\actions\GatewayAbstractAction;
use GuzzleHttp\Client;

class GatewayGetPraticienAction extends GatewayAbstractAction
{
    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {
        $response = $this->client->get('praticiens');  
        return $response;
    }
}