<?php

namespace toubeelib\core\services\patient;

use toubeelib\core\dto\patient\InputPatientDTO;
use toubeelib\core\dto\patient\PatientDTO;

interface PatientServiceInterface
{
    public function getPatientById(string $id): PatientDTO;

}