<?php

namespace toubeelib\core\services\rdv;

class RdvSpecialitePraticienDifferentException extends \Exception
{

    public function __construct($message = "le praticien n'a pas la specialite demandée")
    {
        parent::__construct($message);
    }
}