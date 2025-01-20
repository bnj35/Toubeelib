<?php

namespace toubeelib\core\domain\entities\rdv;

//entities
use toubeelib\core\domain\entities\Entity;

class Rdv extends Entity
{
    protected \DateTimeImmutable $date;
    protected int $duree = 30;
    protected string $patientId;
    protected string $praticienId;
    protected string $speciality;
    protected string $statut;

    public function __construct(string $praticienId, string $patientId, string $speciality, string $date, string $statut = 'default')
    {
        $parsedDate = \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $date);
        if ($parsedDate === false) {
            throw new \InvalidArgumentException("Invalid date format, expected 'Y-m-d H:i:s'");
        }
        $this->date = $parsedDate;
        $this->praticienId = $praticienId;
        $this->patientId = $patientId;
        $this->speciality = $speciality;
        $this->statut = $statut;
    }


    public function getDate(): \DateTimeImmutable
    {
        return $this->date;
    }

    public function getDuree(): int
    {
        return $this->duree;
    }

    public function getPatientID(): string
    {
        return $this->patientId;
    }

    public function setPatientID(string $patientId): void
    {
        $this->patientId = $patientId;
    }

    public function getPraticienID(): string
    {
        return $this->praticienId;
    }

    public function getStatut(): string
    {
        return $this->statut;
    }

    public function getSpeciality(): string
    {
        return $this->speciality;
    }

    public function setSpeciality(string $speciality): void
    {
        $this->speciality = $speciality;
    }

}