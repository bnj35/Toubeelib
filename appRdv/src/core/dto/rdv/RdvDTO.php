<?php

namespace toubeelib\core\dto\rdv;
//entities
use toubeelib\core\domain\entities\patient\Patient;
use toubeelib\core\domain\entities\praticien\Praticien;
use toubeelib\core\domain\entities\Rdv\Rdv;
//DTO
use toubeelib\core\dto\DTO;

class RdvDTO extends DTO
{
    protected string $id;
    protected \DateTimeImmutable $date;
    protected int $duree;
    protected string $praticienId;
    protected string $speciality;
    protected string $patientId;
    protected string $statut;

    public function __construct(Rdv $rdv)
    {
        $this->id = $rdv->getId();
        $this->date = $rdv->getDate();
        $this->duree = $rdv->getDuree();
        $this->praticienId = $rdv->getPraticienId();
        $this->speciality = $rdv->getSpeciality();
        $this->patientId = $rdv->getPatientId();
        $this->statut = $rdv->getStatut();
    }
}