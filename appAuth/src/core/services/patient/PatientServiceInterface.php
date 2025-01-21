<?php

namespace toubeelib\core\services\patient;

use toubeelib\core\dto\patient\InputPatientDTO;
use toubeelib\core\dto\patient\PatientDTO;

interface PatientServiceInterface
{
    public function createPatient(InputPatientDTO $p): PatientDTO;
    public function getPatientById(string $id): PatientDTO;
    public function getRendezVousByPatientId(string $id): array;

}