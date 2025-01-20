<?php

namespace toubeelib\infrastructure\repositories;

use Ramsey\Uuid\Uuid;
use toubeelib\core\domain\entities\praticien\Praticien;
use toubeelib\core\domain\entities\praticien\Specialite;
use toubeelib\core\repositoryInterfaces\PraticienRepositoryInterface;
use toubeelib\core\repositoryInterfaces\RepositoryEntityNotFoundException;

class ArrayPraticienRepository implements PraticienRepositoryInterface
{

    const SPECIALITES = [
        'A' => [
            'ID' => 'A',
            'label' => 'Dentiste',
            'description' => 'Spécialiste des dents'
        ],
        'B' => [
            'ID' => 'B',
            'label' => 'Ophtalmologue',
            'description' => 'Spécialiste des yeux'
        ],
        'C' => [
            'ID' => 'C',
            'label' => 'Généraliste',
            'description' => 'Médecin généraliste'
        ],
        'D' => [
            'ID' => 'D',
            'label' => 'Pédiatre',
            'description' => 'Médecin pour enfants'
        ],
        'E' => [
            'ID' => 'E',
            'label' => 'Médecin du sport',
            'description' => 'Maladies et trausmatismes liés à la pratique sportive'
        ],
    ];

    private array $praticiens = [];

    public function __construct() {
        $this->praticiens['p1'] = new Praticien('Dupont', 'Jean', new Specialite('A', 'Dentiste', 'Spécialiste des dents'));
        $this->praticiens['p1']->setID('p1');

        $this->praticiens['p2'] = new Praticien('Durand', 'Pierre', new Specialite('B', 'Ophtalmologue', 'Spécialiste des yeux'));
        $this->praticiens['p2']->setID('p2');

        $this->praticiens['p3'] = new Praticien('Martin', 'Marie', new Specialite('C', 'Généraliste', 'Médecin généraliste'));
        $this->praticiens['p3']->setID('p3');

    }
    public function getSpecialiteById(string $id): Specialite
    {

        $specialite = self::SPECIALITES[$id] ??
            throw new RepositoryEntityNotFoundException("Specialite $id not found") ;

        return new Specialite($specialite['ID'], $specialite['label'], $specialite['description']);
    }

    public function save(Praticien $praticien): string
    {
        // TODO : prévoir le cas d'une mise à jour - le praticient possède déjà un ID
		$ID = Uuid::uuid4()->toString();
        $praticien->setID($ID);
        $this->praticiens[$ID] = $praticien;
        return $ID;
    }

    public function getPraticienById(string $id): Praticien
    {
        $praticien = $this->praticiens[$id] ??
            throw new RepositoryEntityNotFoundException("Praticien $id not found");

        return $praticien;
    }

    public function deletePraticienById(string $id): void
    {
        if (!isset($this->praticiens[$id])) {
            throw new RepositoryEntityNotFoundException("Praticien $id not found");
        }
        unset($this->praticiens[$id]);
    }

    public function getAllPraticiens(): array
    {
        return array_values($this->praticiens);
    }

    public function searchPraticiensByName(string $name): array
    {
        return array_filter($this->praticiens, function (Praticien $praticien) use ($name) {
            return stripos($praticien->getNom(), $name) !== false || stripos($praticien->getPrenom(), $name) !== false;
        });
    }

    public function getPlanning(string $praticienId, string $startDate, string $endDate, ?string $specialty, ?string $consultationType): array
    {
        return [];
    }

}