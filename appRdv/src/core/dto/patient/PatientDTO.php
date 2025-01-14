<?php

namespace toubeelib\core\dto\patient;

//entities
use toubeelib\core\domain\entities\patient\Patient;
//DTO
use toubeelib\core\dto\DTO;

class PatientDTO extends DTO
{
    protected string $id;
    protected string $nom;
    protected string $prenom;
    protected string $adresse;
    protected string $tel;

    public function __construct(Patient $pa) {
        $this->id = $pa->getID();
        $this->nom = $pa->nom;
        $this->prenom = $pa->prenom;
        $this->adresse = $pa->adresse;
        $this->tel = $pa->tel;
    }

}