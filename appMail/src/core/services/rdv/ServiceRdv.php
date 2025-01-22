<?php

namespace toubeelib\core\services\rdv;
//log
use Psr\Log\LoggerInterface;
//entities
use toubeelib\core\domain\entities\rdv\Rdv;
//dto
use toubeelib\core\dto\rdv\CalendarRdvDTO;
use toubeelib\core\dto\rdv\CreateRdvDTO;
use toubeelib\core\dto\rdv\PlanningPraticienDTO;
use toubeelib\core\dto\rdv\RdvDTO;
//interfaces
use toubeelib\core\services\rdv\ServiceRdvInterface;
use toubeelib\core\repositoryInterfaces\PatientRepositoryInterface;
use toubeelib\core\repositoryInterfaces\RdvRepositoryInterface;
use toubeelib\core\repositoryInterfaces\RepositoryEntityNotFoundException;
use toubeelib\core\repositoryInterfaces\RepositoryInternalServerError;
use toubeelib\core\services\praticien\PraticienInfoServiceInterface;

class ServiceRdv implements ServiceRdvInterface
{
    private PraticienInfoServiceInterface $praticienInfoService;
    private RdvRepositoryInterface $rdvRepository;
    private PatientRepositoryInterface $patientRepository;
    private LoggerInterface $logger;

    public function __construct(PraticienInfoServiceInterface $praticienInfoService, RdvRepositoryInterface $rdvRepository, PatientRepositoryInterface $patientRepository, LoggerInterface $logger)
    {
        $this->praticienInfoService = $praticienInfoService;
        $this->rdvRepository = $rdvRepository;
        $this->patientRepository = $patientRepository;
        $this->logger = $logger;
    }

    public function createRdv(CreateRdvDTO $createRDVDTO): RdvDTO
    {
        try {
            if ($createRDVDTO->praticienId == null) {
                throw new RdvPraticienNotFoundException();
            }
            $praticien = $this->praticienInfoService->getPraticienById($createRDVDTO->praticienId);
            $specialitePraticien = $praticien['specialite'];

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
            throw new RdvInternalServerError($e->getMessage());
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

    public function getPlanningByPraticien(PlanningPraticienDTO $disponibilityPraticienRDVDto): array
    {
        try {
            $rdvs = $this->rdvRepository->getRdvByPraticienId($disponibilityPraticienRDVDto->idPraticien);
            $disponibility = [];
            if ($disponibilityPraticienRDVDto->dateDebut > $disponibilityPraticienRDVDto->dateFin) {
                throw new RdvBadDataException();
            }
            $date = $disponibilityPraticienRDVDto->dateDebut;
            while ($date < $disponibilityPraticienRDVDto->dateFin) {
                $isDispo = true;
                foreach ($rdvs as $rdv) {
                    if ($rdv->getDate() == $date) {
                        $isDispo = false;
                        break;
                    }
                }
                if ($isDispo && $date->format('H') >= 9 && $date->format('H') < 18) {
                    $disponibility[] = $date;
                }
                $date = $date->add(new \DateInterval('PT' . $disponibilityPraticienRDVDto->duree . 'M'));
            }
            return $disponibility;
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

    public function getCalendarRdvByPraticien(CalendarRdvDTO $calendarRdvDTO): array
    {
        try {
            $rdvs = $this->rdvRepository->getRdvByPraticienId($calendarRdvDTO->id);
            $calendar = [];
            foreach ($rdvs as $rdv) {
                if ($rdv->getDate() >= $calendarRdvDTO->date_debut && $rdv->getDate() <= $calendarRdvDTO->date_fin) {
                    $calendar[] = new RdvDTO($rdv);
                }
            }
            return $calendar;
        } catch (RepositoryEntityNotFoundException $e) {
            throw new RdvBadDataException($e->getMessage());
        } catch (RepositoryInternalServerError $e) {
            throw new RdvInternalServerError($e->getMessage());
        }
    }

}