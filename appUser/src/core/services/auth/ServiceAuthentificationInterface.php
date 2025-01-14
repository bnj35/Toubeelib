<?php

namespace toubeelib\core\services\auth;

use toubeelib\core\dto\auth\AuthDTO;
use toubeelib\core\dto\auth\CredentialsDTO;
use toubeelib\core\services\auth\AuthentificationServiceBadDataException;
use toubeelib\core\services\auth\AuthentificationServiceInternalServerErrorException;
use toubeelib\core\services\auth\AuthentificationServiceNotFoundException;

interface ServiceAuthentificationInterface
{
    public function register(CredentialsDTO $credentials, int $role): string;
    public function byCredentials(CredentialsDTO $credentials): AuthDTO;

}