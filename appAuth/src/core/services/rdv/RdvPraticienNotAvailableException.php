<?php

namespace toubeelib\core\services\rdv;

class RdvPraticienNotAvailableException extends \Exception
{

    public function __construct($message = "Praticien indisponible")
    {
        parent::__construct($message);
    }
}