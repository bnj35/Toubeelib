<?php

namespace toubeelib\application\actions;


use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use guzzlehttp\Client;

abstract class GatewayAbstractAction
{

    abstract public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, Client $client): ResponseInterface ;

}