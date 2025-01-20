<?php

namespace toubeelib\core\dto\rdv;

use toubeelib\core\dto\DTO;

class CreateRdvDTO extends DTO
{
    protected string $date;
    protected int $duree;
    protected string $praticienId;
    protected string $patientId;
    protected string $specialite;

    public function __construct(string $date, int $duree, string $praticienId, string $patientId, string $specialite)
    {
        $this->date = $date;
        $this->duree = $duree;
        $this->praticienId = $praticienId;
        $this->patientId = $patientId;
        $this->specialite = $specialite;
    }

}