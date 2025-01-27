<?php

namespace toubeelib\core\dto\patient;

//DTO
use toubeelib\core\dto\DTO;

class InputPatientDTO extends DTO
{
    protected string $email;
    protected string $adresse;
    protected string $tel;


    public function __construct(string $email, string $adresse, string $tel) {
        $this->email = $email;
        $this->adresse = $adresse;
        $this->tel = $tel;
    }

}