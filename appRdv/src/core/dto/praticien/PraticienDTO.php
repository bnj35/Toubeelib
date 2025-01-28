<?php

namespace toubeelib\core\dto\praticien;

use toubeelib\core\domain\entities\praticien\Praticien;
use toubeelib\core\dto\DTO;

class PraticienDTO extends DTO
{
    protected string $id;
    protected string $email;
    protected string $prenom;
    protected string $nom;
    protected string $tel;
    protected string $adresse;
    protected string $specialite_label;

    public function __construct(Praticien $p)
    {
        $this->id = $p->getID();
        $this->email = $p->email;
        $this->prenom = $p->prenom;
        $this->nom = $p->nom;
        $this->tel = $p->tel;
        $this->adresse = $p->adresse;
        $this->specialite_label = $p->specialite->label;
    }
}