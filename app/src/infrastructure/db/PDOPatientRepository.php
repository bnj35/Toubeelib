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
                $stmt = $this->pdo->prepare("UPDATE patient SET adresse = :adresse, tel = :tel, email = :email WHERE id = :id");
            } else {
                $id = Uuid::uuid4()->toString();
                $patient->setID($id);
                $stmt = $this->pdo->prepare("INSERT INTO patient (id, adresse, tel, email) VALUES (:id, :adresse, :tel, :email)");
            }
            $stmt->execute([
                'id' => $patient->getID(),
                'adresse' => $patient->getAdresse(),
                'tel' => $patient->getTel(),
                'email' => $patient->getEmail()
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
            $p = new Patient($patient['adresse'], $patient['tel'], $patient['email']);
            $p->setID($patient['id']);
            return $p;
        } catch (\PDOException $e) {
            throw new RepositoryInternalServerError("Error while fetching patient");
        }
    }
}