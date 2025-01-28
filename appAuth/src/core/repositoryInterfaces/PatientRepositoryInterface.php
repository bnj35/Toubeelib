<?php

namespace toubeelib\core\repositoryInterfaces;

use toubeelib\core\domain\entities\patient\Patient;

interface PatientRepositoryInterface
{
    public function getPatientById(string $id): Patient;
}
