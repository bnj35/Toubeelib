<?php

namespace toubeelib\core\services\rdv;

interface AuthorizationRdvServiceInterface
{
    public function isGranted(string $user_id, int $operation, string $ressource_id, int $role): bool;

}