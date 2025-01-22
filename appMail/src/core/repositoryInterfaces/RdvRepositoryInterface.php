<?php

namespace toubeelib\core\repositoryInterfaces;

use toubeelib\core\domain\entities\rdv\Rdv;

interface RdvRepositoryInterface
{
    public function save(Rdv $rdv): string;
    public function getRdvById(string $id): Rdv;
    public function getRdvByPraticienId(string $praticienId): array;
    public function getRdvByPatientId(string $id);

}