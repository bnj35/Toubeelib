<?php

namespace toubeelib\core\services\rdv;

use toubeelib\core\repositoryInterfaces\RdvRepositoryInterface;

class AuthorizationRdvService implements AuthorizationRdvServiceInterface
{
    protected RdvRepositoryInterface $RdvRepository;

    public function __construct(RdvRepositoryInterface $RdvRepository)
    {
        $this->RdvRepository = $RdvRepository;
    }

    public function isGranted(string $user_id, int $operation, string $ressource_id, int $role): bool
    {
        $rdv = $this->RdvRepository->getRdvById($ressource_id);

        if($role === 0 && $user_id === $rdv->getPatientId()){
            return true;
        }else if($role === 10 && $user_id === $rdv->getPraticienID()) {
            return true;
        }else
            return false;
    }
}