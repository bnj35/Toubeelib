<?php

namespace toubeelib\core\services\praticien;

//praticien
use toubeelib\core\domain\entities\praticien\Praticien;
//dto
use toubeelib\core\dto\praticien\InputPraticienDTO;
use toubeelib\core\dto\praticien\PraticienDTO;
use toubeelib\core\dto\praticien\SpecialiteDTO;
//interfaces
use toubeelib\core\repositoryInterfaces\PraticienRepositoryInterface;
use toubeelib\core\repositoryInterfaces\RepositoryEntityNotFoundException;
use toubeelib\core\repositoryInterfaces\RepositoryInternalServerError;

class ServicePraticien implements ServicePraticienInterface
{
    private PraticienRepositoryInterface $praticienRepository;

    public function __construct(PraticienRepositoryInterface $praticienRepository)
    {
        $this->praticienRepository = $praticienRepository;
    }

    public function getPraticienById(string $id): PraticienDTO
    {
        try {
            $praticien = $this->praticienRepository->getPraticienById($id);
            return new PraticienDTO($praticien);
        } catch(RepositoryEntityNotFoundException $e) {
            throw new ServicePraticienInvalidDataException('invalid Praticien ID');
        } catch(RepositoryInternalServerError $e) {
            throw new ServicePraticienInternalServerError($e->getMessage());
        }
    }

    public function getSpecialiteById(string $id): SpecialiteDTO
    {
        try {
            $specialite = $this->praticienRepository->getSpecialiteById($id);
            return $specialite->toDTO();
        } catch(RepositoryEntityNotFoundException $e) {
            throw new ServicePraticienInvalidDataException('invalid Specialite ID');
        } catch(RepositoryInternalServerError $e) {
            throw new ServicePraticienInternalServerError( $e->getMessage());
        }
    }

    public function getAllPraticiens(): array
    {
        try {
            $praticiens = $this->praticienRepository->getAllPraticiens();
            $praticiensDTO = [];
            foreach ($praticiens as $praticien) {
                $praticiensDTO[] = $praticien->toDTO();
            }
            return $praticiensDTO;
        } catch(RepositoryInternalServerError $e) {
            throw new ServicePraticienInternalServerError($e->getMessage());
        }
    }

    public function createPraticien(InputPraticienDTO $p): PraticienDTO
    {
        try{
            $praticien = new Praticien($p->nom, $p->prenom, $p->adresse, $p->tel);
            $specialite = $this->praticienRepository->getSpecialiteById($p->specialite);

            $praticien->setSpecialite($specialite);
            $this->praticienRepository->save($praticien);
        }catch (RepositoryEntityNotFoundException $e) {
            throw new ServicePraticienInvalidDataException('invalid Specialite ID');
        } catch (RepositoryInternalServerError $e) {
            throw new ServicePraticienInternalServerError($e->getMessage());
        }
        return $praticien->toDTO();
    }


    public function searchPraticiens(string $search): array
    {
        try {
            $praticiens = $this->praticienRepository->searchPraticiens($search);
            $praticiensDTO = [];
            foreach ($praticiens as $praticien) {
                $praticiensDTO[] = $praticien->toDTO();
            }
            return $praticiensDTO;
        } catch(RepositoryInternalServerError $e) {
            throw new ServicePraticienInternalServerError($e->getMessage());
        }
    }

    public function getSpecialiteIdByPraticienId(string $praticienId): string
    {
        try {
            $praticien = $this->praticienRepository->getPraticienById($praticienId);
            return $praticien->getSpecialite();
        } catch (RepositoryEntityNotFoundException $e) {
            throw new ServicePraticienInvalidDataException('invalid Praticien ID');
        } catch (RepositoryInternalServerError $e) {
            throw new ServicePraticienInternalServerError($e->getMessage());
        }
    }
}