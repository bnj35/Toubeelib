<?php

namespace toubeelib\core\dto\praticien;

use toubeelib\core\dto\DTO;

class InputPraticienDTO extends DTO
{
    protected string $emil;
    protected string $nom;
    protected string $prenom;
    protected string $adresse;
    protected string $tel;
    protected string $specialite;

    public function __construct(string $email, string $nom, string $prenom, string $adresse, string $tel, string $specialite) {
        $this->email = $email;
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->adresse = $adresse;
        $this->tel = $tel;
        $this->specialite = $specialite;
    }
}