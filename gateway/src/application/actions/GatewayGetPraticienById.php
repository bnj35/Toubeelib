<?php

namespace toubeelib\application\actions;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Exception\HttpNotFoundException;
use toubeelib\application\actions\GatewayAbstractAction;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

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
        try {
            $response = $this->client->get('praticiens/'.$id);
            return $response;
        } catch (RequestException $e) {
            if ($e->getResponse() && $e->getResponse()->getStatusCode() == 404) {
                throw new HttpNotFoundException($rq, "Praticien not found");
            }
            throw $e;
        }
    }
}