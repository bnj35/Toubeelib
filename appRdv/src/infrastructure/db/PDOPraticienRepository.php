<?php

namespace toubeelib\infrastructure\db;

use Ramsey\Uuid\Uuid;
use toubeelib\core\domain\entities\praticien\Praticien;
use toubeelib\core\domain\entities\praticien\Specialite;
use toubeelib\core\repositoryInterfaces\PraticienRepositoryInterface;
use toubeelib\core\repositoryInterfaces\RepositoryEntityNotFoundException;
use toubeelib\core\repositoryInterfaces\RepositoryInternalServerError;

class PDOPraticienRepository implements PraticienRepositoryInterface
{
    private \PDO $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getSpecialiteById(string $id): Specialite
    {
        try {
            $query = $this->pdo->prepare('SELECT * FROM specialite WHERE id = :id');
            $query->execute(['id' => $id]);
            $specialite = $query->fetch();
            if ($specialite === false) {
                throw new RepositoryEntityNotFoundException("Specialite not found");
            }
            return new Specialite($specialite['id'], $specialite['label'], $specialite['description']);
        } catch (\PDOException $e) {
            throw new RepositoryInternalServerError("Error while fetching specialite");
        }

    }

    public function save(Praticien $praticien): string
    {
        try{
            if ($praticien->getID() !== null) {
                $stmt = $this->pdo->prepare("UPDATE praticien SET nom = :nom, prenom = :prenom, adresse = :adresse, telephone = :telephone, specialite_id = :specialite_id WHERE id = :id");
            }else{
                $id = Uuid::uuid4()->toString();
                $praticien->setID($id);
                $stmt = $this->pdo->prepare("INSERT INTO praticien (id, nom, prenom, adresse, telephone, specialite_id) VALUES (:id, :nom, :prenom, :adresse, :telephone, :specialite_id)");
            }
            $stmt->execute([
                'id' => $praticien->getID(),
                'nom' => $praticien->getNom(),
                'prenom' => $praticien->getPrenom(),
                'adresse' => $praticien->getAdresse(),
                'telephone' => $praticien->getTel(),
                'specialite_id' => $praticien->getSpecialite()
            ]);
            return $praticien->getID();
        } catch (\PDOException $e) {
            throw new RepositoryInternalServerError("Error while saving praticien");
        }
    }

    public function getPraticienById(string $id): Praticien
    {
        try{
            $query = $this->pdo->prepare('SELECT * FROM praticien WHERE id = :id');
            $query->execute(['id' => $id]);
            $praticien = $query->fetch();
            if ($praticien === false) {
                throw new RepositoryEntityNotFoundException("Praticien not found");
            }
            $p =  new Praticien($praticien['nom'], $praticien['prenom'], $praticien['adresse'], $praticien['telephone']);
            $p->setID($praticien['id']);
            $p->setSpecialite($this->getSpecialiteById($praticien['specialite_id']));
            return $p;
        } catch (\PDOException $e) {
            throw new RepositoryInternalServerError("Error while fetching praticien");
        }

    }

    public function getAllPraticiens(): array
    {
        try{
            $query = $this->pdo->query('SELECT * FROM praticien');
            $praticiens = $query->fetchAll();
            $result = [];
            foreach ($praticiens as $praticien) {
                $p = new Praticien($praticien['nom'], $praticien['prenom'], $praticien['adresse'], $praticien['telephone']);
                $p->setID($praticien['id']);
                $p->setSpecialite($this->getSpecialiteById($praticien['specialite_id']));
                $result[] = $p;
            }
            return $result;
        } catch (\PDOException $e) {
            throw new RepositoryInternalServerError("Error while fetching praticiens");
        }
    }

    public function searchPraticiens(string $search): array
    {
        try{
            $query = $this->pdo->prepare('SELECT * FROM praticien WHERE nom LIKE :search OR prenom LIKE :search');
            $query->execute(['search' => "%$search%"]);
            $praticiens = $query->fetchAll();
            $result = [];
            foreach ($praticiens as $praticien) {
                $p = new Praticien($praticien['nom'], $praticien['prenom'], $praticien['adresse'], $praticien['telephone']);
                $p->setID($praticien['id']);
                $p->setSpecialite($this->getSpecialiteById($praticien['specialite_id']));
                $result[] = $p;
            }
            return $result;
        } catch (\PDOException $e) {
            throw new RepositoryInternalServerError("Error while fetching praticiens");
        }
    }
}