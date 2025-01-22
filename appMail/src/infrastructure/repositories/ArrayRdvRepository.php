<?php

namespace toubeelib\infrastructure\repositories;

use Ramsey\Uuid\Uuid;
use toubeelib\core\domain\entities\rdv\Rdv;
use toubeelib\core\domain\entities\praticien\Praticien;
use toubeelib\core\domain\entities\praticien\Specialite; // Add this line
use toubeelib\core\domain\entities\patient\Patient;
use toubeelib\core\repositoryInterfaces\RdvRepositoryInterface;
use toubeelib\core\repositoryInterfaces\RepositoryEntityNotFoundException;

class ArrayRdvRepository implements RdvRepositoryInterface
{
    private array $rdvs = [];
    private array $praticiens = [];
    private array $patients = [];

    public function __construct() {
        $specialite = new Specialite('A', 'Specialite Name', 'Specialite Description'); // Create a Specialite object
        $praticien = new Praticien('p1', 'Praticien Name', $specialite);
        $patient = new Patient('pa1', 'Patient Name', 'Patient Info');
        $this->praticiens['p1'] = $praticien;
        $this->patients['pa1'] = $patient;

        $r1 = new Rdv(\DateTimeImmutable::createFromFormat('Y-m-d H:i', '2024-09-02 09:00'), '09:00', $praticien, $patient);
        $r1->setID('r1');
        $r2 = new Rdv(\DateTimeImmutable::createFromFormat('Y-m-d H:i', '2024-09-02 10:00'), '10:00', $praticien, $patient);
        $r2->setID('r2');
        $r3 = new Rdv(\DateTimeImmutable::createFromFormat('Y-m-d H:i', '2024-09-02 09:30'), '09:30', $praticien, $patient);
        $r3->setID('r3');

        $this->rdvs  = ['r1'=> $r1, 'r2'=>$r2, 'r3'=> $r3 ];
    }

    public function save(Rdv $rdv): string
    {
        $id = Uuid::uuid4()->toString();
        $rdv->setID($id);
        $this->rdvs[$id] = $rdv;
        return $id;
    }

    public function getRdvById(string $id): Rdv
    {
        if (!isset($this->rdvs[$id])) {
            throw new RepositoryEntityNotFoundException("Rdv not found");
        }
        return $this->rdvs[$id];
    }

    private function getPraticienById(string $id): Praticien
    {
        if (!isset($this->praticiens[$id])) {
            throw new RepositoryEntityNotFoundException("Praticien not found");
        }
        return $this->praticiens[$id];
    }

    private function getPatientById(string $id): Patient
    {
        if (!isset($this->patients[$id])) {
            throw new RepositoryEntityNotFoundException("Patient not found");
        }
        return $this->patients[$id];
    }
}