<?php

namespace toubeelib\core\dto\rendez_vous;

use toubeelib\core\dto\DTO;

class DisponibilityPraticienRendezVousDTO extends DTO
{

    protected string $PraticienId;
    protected \DateTimeImmutable $start;
    protected \DateTimeImmutable $end;
    protected int $duration;

    public function __construct(string $PraticienId, \DateTimeImmutable $start, \DateTimeImmutable $end , int $duration)
    {
        $this->PraticienId = $PraticienId;
        $this->start = $start;
        $this->end = $end;
        $this->duration = $duration;
    }


}