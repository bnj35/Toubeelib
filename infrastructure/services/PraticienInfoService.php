<?php

namespace toubeelib\infrastructure\services;

use GuzzleHttp\Client;
use toubeelib\core\services\praticien\PraticienInfoServiceInterface;

class PraticienInfoService implements PraticienInfoServiceInterface
{
    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function getPraticienById(string $id): array
    {
        $response = $this->client->get("/praticiens/{$id}");
        return json_decode($response->getBody()->getContents(), true);
    }
}
