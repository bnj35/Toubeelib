<?php

namespace toubeelib\core\services\praticien;

interface PraticienInfoServiceInterface
{
    public function getPraticienById(string $id): array;
}
