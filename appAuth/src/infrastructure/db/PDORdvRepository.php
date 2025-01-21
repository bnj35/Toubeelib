<?php

namespace toubeelib\infrastructure\db;

use Ramsey\Uuid\Uuid;
use toubeelib\core\domain\entities\rdv\Rdv;
use toubeelib\core\repositoryInterfaces\RdvRepositoryInterface;
use toubeelib\core\repositoryInterfaces\RepositoryEntityNotFoundException;
use toubeelib\core\repositoryInterfaces\RepositoryInternalServerError;

class PDORdvRepository implements RdvRepositoryInterface
{
    private \PDO $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function save(Rdv $rdv): string
    {
        try{
            if ($rdv->getID() !== null) {
                $stmt = $this->pdo->prepare("UPDATE rdv SET date = :rdv_date, duree = :duree, patient_id = :patient_id, praticien_id = :praticien_id, specialite_id = :specialite_id, status = :status WHERE id = :id");
            }else{
                $id = Uuid::uuid4()->toString();
                $rdv->setID($id);
                $stmt = $this->pdo->prepare("INSERT INTO rdv (id, rdv_date, duree, patient_id, praticien_id, specialite_id, status) VALUES (:id, :rdv_date, :duree, :patient_id, :praticien_id, :specialite_id, :status)");
            }
            $stmt->execute([
                'id' => $rdv->getID(),
                'rdv_date' => $rdv->getDate()->format('Y-m-d H:i:s'),
                'duree' => $rdv->getDuree(),
                'patient_id' => $rdv->getPatientID(),
                'praticien_id' => $rdv->getPraticienID(),
                'specialite_id' => $rdv->getSpeciality(),
                'status' => $rdv->getStatut()
            ]);
            return $rdv->getID();
        } catch (\PDOException $e) {
            throw new RepositoryInternalServerError("Error while saving rdv");
        }
    }

    public function getRdvById(string $id): Rdv
    {
        try{
            $stmt = $this->pdo->prepare("SELECT * FROM rdv WHERE id = :id");
            $stmt->execute(['id' => $id]);
            $rdv = $stmt->fetch();
            if ($rdv === false) {
                throw new RepositoryEntityNotFoundException("rdv not found");
            }
            $rdvT =  new Rdv($rdv['praticien_id'], $rdv['patient_id'], $rdv['specialite_id'], $rdv['rdv_date'], $rdv['status']);
            $rdvT->setID($rdv['id']);
            return $rdvT;
        } catch (\PDOException $e) {
            throw new RepositoryInternalServerError("Error while fetching rdv");
        }

    }

    public function getRdvByPraticienId(string $praticienId): array
    {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM rdv WHERE praticien_id = :praticien_id");
            $stmt->execute(['praticien_id' => $praticienId]);
            $rdvs = $stmt->fetchAll();
            $rdvsArray = [];
            foreach ($rdvs as $rdv) {
                $rdva = new Rdv($rdv['praticien_id'], $rdv['patient_id'], $rdv['specialite_id'], $rdv['rdv_date'], $rdv['status']);
                $rdva->setID($rdv['id']);
                $rdvsArray[] = $rdva;
            }
            return $rdvsArray;
        } catch (\PDOException $e) {
            throw new RepositoryInternalServerError("Error while fetching rdv");
        }
    }

    public function getRdvByPatientId(string $id): array
    {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM rdv WHERE patient_id = :patient_id");
            $stmt->execute(['patient_id' => $id]);
            $rdvs = $stmt->fetchAll();
            $rdvsArray = [];
            foreach ($rdvs as $rdv) {
                $rdva = new Rdv($rdv['praticien_id'], $rdv['patient_id'], $rdv['specialite_id'], $rdv['rdv_date'], $rdv['status']);
                $rdva->setID($rdv['id']);
                $rdvsArray[] = $rdva;
            }
            return $rdvsArray;
        } catch (\PDOException $e) {
            throw new RepositoryInternalServerError("Error while fetching rdv");
        }
    }
}