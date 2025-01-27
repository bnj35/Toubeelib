<?php

namespace toubeelib\infrastructure\db;

use Ramsey\Uuid\Uuid;
use toubeelib\core\domain\entities\patient\Patient;
use toubeelib\core\repositoryInterfaces\PatientRepositoryInterface;
use toubeelib\core\repositoryInterfaces\RepositoryEntityNotFoundException;
use toubeelib\core\repositoryInterfaces\RepositoryInternalServerError;

class PDOPatientRepository implements PatientRepositoryInterface
{
    private \PDO $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function save(Patient $patient): string
    {
        try {
            if ($patient->getID() !== null) {
                $stmt = $this->pdo->prepare("UPDATE patient SET email = :email, adresse = :adresse, telephone = :telephone WHERE id = :id");
            } else {
                $id = Uuid::uuid4()->toString();
                $patient->setID($id);
                $stmt = $this->pdo->prepare("INSERT INTO patient (id, email, adresse, telephone) VALUES (:id, :email, :adresse, :telephone)");
            }
            $stmt->execute([
                'id' => $patient->getID(),
                'email' => $patient->getEmail(),
                'adresse' => $patient->getAdresse(),
                'telephone' => $patient->getTel()
            ]);
        } catch (\PDOException $e) {
            throw new RepositoryInternalServerError("Error while saving patient");
        }

        return $patient->getID();
    }

    public function getPatientById(string $id): Patient
    {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM patient WHERE id = :id");
            $stmt->execute(['id' => $id]);
            $patient = $stmt->fetch();
            if ($patient === false) {
                throw new RepositoryEntityNotFoundException("Patient not found");
            }
            $p = new Patient($patient['email'], $patient['adresse'], $patient['telephone']);
            $p->setID($patient['id']);
            return $p;
        } catch (\PDOException $e) {
            throw new RepositoryInternalServerError("Error while fetching patient");
        }
    }
}