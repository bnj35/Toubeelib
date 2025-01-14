<?php

namespace toubeelib\core\dto\rdv;

use toubeelib\core\dto\DTO;

class PlanningPraticienDTO extends DTO
{

    protected string $idPraticien;
    protected \DateTimeImmutable $dateDebut;
    protected \DateTimeImmutable $dateFin;
    protected int $duree;

    public function __construct(string $idPraticien, \DateTimeImmutable $dateDebut, \DateTimeImmutable $dateFin , int $duree)
    {
        $this->idPraticien = $idPraticien;
        $this->dateDebut = $dateDebut;
        $this->dateFin = $dateFin;
        $this->duree = $duree;
    }


}