<?php

namespace toubeelib\core\domain\entities\praticien;

//entities
use toubeelib\core\domain\entities\Entity;
//dto
use toubeelib\core\dto\praticien\SpecialiteDTO;

class Specialite extends Entity
{
    protected string $id;
    protected string $label;
    protected string $description;

    public function __construct(string $ID, string $label, string $description)
    {
        $this->id = $ID;
        $this->label = $label;
        $this->description = $description;
    }

    public function toDTO(): SpecialiteDTO
    {
        return new SpecialiteDTO($this->id, $this->label, $this->description);

    }
}