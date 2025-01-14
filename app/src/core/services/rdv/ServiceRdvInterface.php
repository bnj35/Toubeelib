<?php

namespace toubeelib\core\services\rdv;

use toubeelib\core\dto\rdv\CancelRdvDTO;
use toubeelib\core\dto\rdv\CreateRdvDTO;
use toubeelib\core\dto\rdv\PlanningPraticienDTO;
use toubeelib\core\dto\rdv\RdvDTO;
use toubeelib\core\dto\rdv\UpdatePatientRdvDTO;
use toubeelib\core\dto\rdv\UpdateSpecialityRdvDTO;

interface ServiceRdvInterface
{
    public function createRdv(CreateRdvDTO $createRDVDTO): RdvDTO;

    public function getRdvById(string $id): RdvDTO;

    public function getPlanningByPraticien(PlanningPraticienDTO $disponibilityPraticienRDVDto): array;

    public function getRdvByPraticienId(string $id): array;

}