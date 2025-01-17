<?php

namespace toubeelib\application\actions;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

use toubeelib\application\actions\GatewayAbstractAction;
use GuzzleHttp\Client;

class GatewayGetPraticienByIdAction extends GatewayAbstractAction
{
    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {   
        $id = $args['id'];
        $response = $this->client->get('praticiens/'.$id);  
        return $response;
    }
}