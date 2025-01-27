<?php

namespace toubeelib\core\domain\entities\patient;

//entities
use toubeelib\core\domain\entities\Entity;

class Patient extends Entity
{
    protected string $email;
    protected string $adresse;
    protected string $tel;

    public function __construct(string $email, string $adresse, string $tel)
    {
        $this->email = $email;
        $this->adresse = $adresse;
        $this->tel = $tel;
    }

    public function getEmail(): string
    {
        return $this->email;
    }
    public function getAdresse(): string
    {
        return $this->adresse;
    }

    public function getTel(): string
    {
        return $this->tel;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function setAdresse(string $adresse): void
    {
        $this->adresse = $adresse;
    }

    public function setTel(string $tel): void
    {
        $this->tel = $tel;
    }

}