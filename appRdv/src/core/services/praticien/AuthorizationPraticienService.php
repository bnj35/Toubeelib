<?php

namespace toubeelib\core\services\praticien;

class AuthorizationPraticienService implements AuthorizationPraticienServiceInterface
{

    public function isGranted(string $user_id, int $operation, string $ressource_id, int $role): bool
    {
        return ($role === 10 && $user_id === $ressource_id)|| $role === 5;
    }
}