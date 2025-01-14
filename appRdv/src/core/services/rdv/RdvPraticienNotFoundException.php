<?php

namespace toubeelib\core\services\rdv;

class RdvPraticienNotFoundException extends \Exception
{
    public function __construct($message = "Praticien introvable")
    {
        parent::__construct($message);
    }
}