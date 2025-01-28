<?php

namespace toubeelib\core\services\patient;


class AuthorizationPatientService implements AuthorizationPatientServiceInterface
{

    public function isGranted(string $user_id, int $operation, string $ressource_id, int $role): bool
    {
        if ($role == 0 && $user_id == $ressource_id) {
            return true;
        } else {
            return false;
        }
    }
}