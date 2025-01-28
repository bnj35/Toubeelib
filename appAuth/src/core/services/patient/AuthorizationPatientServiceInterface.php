<?php

namespace toubeelib\core\services\patient;

interface AuthorizationPatientServiceInterface
{
    public function isGranted(string $user_id, int $operation, string $ressource_id, int $role): bool;

}