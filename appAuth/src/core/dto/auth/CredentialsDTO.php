<?php

namespace toubeelib\core\dto\auth;

//dto
use toubeelib\core\dto\DTO;

class CredentialsDTO extends DTO
{
    protected string $email;
    protected string $password;

    public function __construct(string $email, string $password)
    {
        $this->email = $email;
        $this->password = $password;
    }

}