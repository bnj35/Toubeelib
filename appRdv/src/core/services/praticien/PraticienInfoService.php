<?php

namespace toubeelib\core\services\praticien;

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
        try {
            $response = $this->client->get("/praticiens/{$id}");
            $data = json_decode($response->getBody()->getContents(), true);
            $praticien = $data['praticien'];
            return $praticien;
        } catch (\Exception $e) {
            return [
                'error' => 'Praticien not found',
                'message' => $e->getMessage()
            ];
        }
    }

}
