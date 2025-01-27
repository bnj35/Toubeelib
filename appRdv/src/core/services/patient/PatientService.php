<?php

namespace toubeelib\core\services\patient;

//entities
use toubeelib\core\domain\entities\patient\Patient;
//dto
use toubeelib\core\dto\patient\InputPatientDTO;
use toubeelib\core\dto\patient\PatientDTO;
use toubeelib\core\dto\rdv\RdvDTO;
//interfaces
use toubeelib\core\repositoryInterfaces\PatientRepositoryInterface;
use toubeelib\core\repositoryInterfaces\RdvRepositoryInterface;
use toubeelib\core\repositoryInterfaces\RepositoryEntityNotFoundException;
use toubeelib\core\repositoryInterfaces\RepositoryInternalServerError;

class PatientService implements PatientServiceInterface
{
    private PatientRepositoryInterface $patientRepository;
    private RdvRepositoryInterface $RdvRepository;

    public function __construct(PatientRepositoryInterface $patientRepository, RdvRepositoryInterface $RdvRepository)
    {
        $this->patientRepository = $patientRepository;
        $this->RdvRepository = $RdvRepository;
    }


    public function getPatientById(string $id): PatientDTO
    {
        try {
            $patient = $this->patientRepository->getPatientById($id);
            return new PatientDTO($patient);
        } catch(RepositoryEntityNotFoundException $e) {
            throw new ServicePatientInvalidDataException('invalid Patient ID');
        } catch(RepositoryInternalServerError $e) {
            throw new ServicePatientInternalServerError($e->getMessage());
        }

    }

    public function getRdvByPatientId(string $id): array
    {
        try {
            $rdvs = $this->RdvRepository->getRdvByPatientId($id);
            // convert to DTO
            $rdvDTOs = [];
            foreach ($rdvs as $rdv) {
                $rdvDTO = new RdvDTO($rdv);
                $rdvDTOs[] = $rdvDTO;
            }
            return $rdvDTOs;
        } catch(RepositoryEntityNotFoundException $e) {
            throw new ServicePatientInvalidDataException('invalid Patient ID');
        } catch(RepositoryInternalServerError $e) {
            throw new ServicePatientInternalServerError($e->getMessage());
        }
    }

}