<?php

namespace toubeelib\core\services\praticien;

interface AuthorizationPraticienServiceInterface
{
    public function isGranted(string $user_id, int $operation, string $ressource_id, int $role): bool;

}