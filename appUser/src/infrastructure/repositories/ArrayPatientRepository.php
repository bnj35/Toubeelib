<?php

namespace toubeelib\infrastructure\repositories;

use Ramsey\Uuid\Uuid;
use toubeelib\core\domain\entities\patient\Patient;
use toubeelib\core\repositoryInterfaces\PatientRepositoryInterface;
use toubeelib\core\repositoryInterfaces\RepositoryEntityNotFoundException;

class ArrayPatientRepository implements PatientRepositoryInterface
{

    private array $patients = [];

    public function __construct()
    {
        $this->patients['pa1'] = new Patient('Mbappé', 'Kilian', '7 rue de la paix', '0123456789');
        $this->patients['pa1']->setID('pa1');
        $this->patients['pa2'] = new Patient('Neymar', 'Jr', '8 rue de la paix', '0123456789');
        $this->patients['pa2']->setID('pa2');
        $this->patients['pa3'] = new Patient('Messi', 'Lionel', '9 rue de la paix', '0123456789');
        $this->patients['pa3']->setID('pa3');
    }

    public function save(Patient $patient): string
    {
        if ($patient->getID() !== null && isset($this->patients[$patient->getID()])) {
            $this->patients[$patient->getID()] = $patient;
        }else{
            $id = Uuid::uuid4()->toString();
            $patient->setID($id);
            $this->patients[$id] = $patient;
        }
        return $patient->getID();
    }

    public function getPatientById(string $id): Patient
    {
        if (isset($this->patients[$id])) {
            return $this->patients[$id];
        }
        throw new RepositoryEntityNotFoundException('Patient not found');
    }
}