<?php

namespace toubeelib\core\services\rdv;
//log
use Psr\Log\LoggerInterface;
//entities
use toubeelib\core\domain\entities\rdv\Rdv;
//dto
use toubeelib\core\dto\rdv\CreateRdvDTO;
use toubeelib\core\dto\rdv\PlanningPraticienDTO;
use toubeelib\core\dto\rdv\RdvDTO;
//interfaces
use toubeelib\core\services\rdv\ServiceRdvInterface;
use toubeelib\core\repositoryInterfaces\PatientRepositoryInterface;
use toubeelib\core\repositoryInterfaces\PraticienRepositoryInterface;
use toubeelib\core\repositoryInterfaces\RdvRepositoryInterface;
use toubeelib\core\repositoryInterfaces\RepositoryEntityNotFoundException;
use toubeelib\core\repositoryInterfaces\RepositoryInternalServerError;

class ServiceRdv implements ServiceRdvInterface
{
    private PraticienRepositoryInterface $praticienRepository;
    private RdvRepositoryInterface $rdvRepository;

    private PatientRepositoryInterface $patientRepository;
    private LoggerInterface $logger;

    public function __construct(PraticienRepositoryInterface $praticienRepository, RdvRepositoryInterface $rdvRepository, PatientRepositoryInterface $patientRepository,LoggerInterface $logger)
    {
        $this->praticienRepository = $praticienRepository;
        $this->rdvRepository = $rdvRepository;
        $this->patientRepository = $patientRepository;
        $this->logger = $logger;
    }

    public function createRdv(CreateRdvDTO $createRDVDTO): RdvDTO
    {
        try{
            if ($createRDVDTO->praticienId == null) {
                throw new RdvPraticienNotFoundException();
            }
            $praticien = $this->praticienRepository->getPraticienById($createRDVDTO->praticienId);
            $specialitePraticien = $praticien->getSpecialite();

            if ($createRDVDTO->specialite != $specialitePraticien) {
                throw new RdvSpecialitePraticienDifferentException($createRDVDTO->specialite . '!=' . $specialitePraticien);
            }

            $rdvs = $this->rdvRepository->getRdvByPraticienId($createRDVDTO->praticienId);
            if ($rdvs != null) {
                foreach ($rdvs as $rdv) {
                    if ($rdv->getDate()->format('Y-m-d H:i:s') == $createRDVDTO->date) {
                        throw new RdvPraticienNotAvailableException();
                    }
                }
            }

            $Rdv = new Rdv(
                $createRDVDTO->praticienId,
                $createRDVDTO->patientId,
                $specialitePraticien,
                $createRDVDTO->date,
                'scheduled' 
            );

            $this->rdvRepository->save($Rdv);
            return new RdvDTO($Rdv);
            
        } catch (RepositoryEntityNotFoundException $e) {
            throw new RdvBadDataException($e->getMessage());
        } catch (RepositoryInternalServerError $e) {
            throw new RdvInternalServerError( $e->getMessage());
        }
    }

    public function getRdvById(string $id): RdvDTO
    {
        try {
            $rdv = $this->rdvRepository->getRdvById($id);
            return new RdvDTO($rdv);

        } catch (RepositoryEntityNotFoundException $e) {
            throw new RdvNotFoundException('rdv not found');
        } catch (RepositoryInternalServerError $e) {
            throw new RdvInternalServerError($e->getMessage());
        }

    }

    public function getPlanningByPraticien(PlanningPraticienDTO $PlanningRdvDTO): array
    {
        try {
            $rdvs = $this->rdvRepository->getRdvByPraticienId($PlanningRdvDTO->id);
            var_dump($rdvs);
            $planning = [];

            if ($PlanningRdvDTO->date_debut > $PlanningRdvDTO->date_fin) {
                throw new RdvBadDataException();
            }

            $date = $PlanningRdvDTO->date_debut;
            while ($date < $PlanningRdvDTO->date_fin) {
                $isDispo = true;
                foreach ($rdvs as $rdv) {
                    if ($rdv->getDate() == $date) {
                        $isDispo = false;
                        break;
                    }
                }
                if ($isDispo && $date->format('H') >= 9 && $date->format('H') < 18) {
                    $planning[] = $date;
                } else {
                    $planning[] = $date->format('Y-m-d H:i:s') . " rdv already scheduled";
                }
                $date = $date->add(new \DateInterval('PT' . $PlanningRdvDTO->duration . 'M'));
            }
            return $planning;
        } catch (RepositoryEntityNotFoundException $e) {
            throw new RdvBadDataException($e->getMessage());
        } catch (RepositoryInternalServerError $e) {
            throw new RdvInternalServerError($e->getMessage());
        }
    }

    public function getRdvByPraticienId(string $id): array
    {
        try {
            return $this->rdvRepository->getRdvByPraticienId($id);
        } catch (RepositoryEntityNotFoundException $e) {
            throw new RdvBadDataException($e->getMessage());
        } catch (RepositoryInternalServerError $e) {
            throw new RdvInternalServerError($e->getMessage());
        }
    }
}