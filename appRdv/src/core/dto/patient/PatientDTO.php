<?php

namespace toubeelib\core\dto\patient;

//entities
use toubeelib\core\domain\entities\patient\Patient;
//DTO
use toubeelib\core\dto\DTO;

class PatientDTO extends DTO
{
    protected string $id;
    protected string $email;
    protected string $adresse;
    protected string $tel;

    public function __construct(Patient $pa) {
        $this->id = $pa->getID();
        $this->email = $pa->email;
        $this->adresse = $pa->adresse;
        $this->tel = $pa->tel;
    }

}