<?php

namespace toubeelib\core\dto\rdv;

use toubeelib\core\dto\DTO;

class PlanningPraticienDTO extends DTO
{
    protected string $id;
    protected \DateTimeImmutable $date_debut;
    protected \DateTimeImmutable $date_fin;
    protected int $duration;
    
    public function __construct(string $id, \DateTimeImmutable $date_debut, \DateTimeImmutable $date_fin, int $duration)
    {
        $this->id = $id;
        $this->date_debut = $date_debut;
        $this->date_fin = $date_fin;
        $this->duration = $duration;
    }

}