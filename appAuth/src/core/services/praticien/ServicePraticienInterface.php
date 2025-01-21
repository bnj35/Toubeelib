<?php

namespace toubeelib\core\services\praticien;

//dto
use toubeelib\core\dto\praticien\InputPraticienDTO;
use toubeelib\core\dto\praticien\PraticienDTO;
use toubeelib\core\dto\praticien\SpecialiteDTO;

interface ServicePraticienInterface
{

    public function createPraticien(InputPraticienDTO $p): PraticienDTO;
    public function getPraticienById(string $id): PraticienDTO;
    public function getSpecialiteById(string $id): SpecialiteDTO;
    public function getAllPraticiens(): array;
    public function searchPraticiens(string $search): array;
    public function getSpecialiteIdByPraticienId(string $praticienId): string;


}