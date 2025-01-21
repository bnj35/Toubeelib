<?php 

namespace toubeelib\core\dto;

class InputRdvDTO extends DTO
{
    protected string $date;
    protected string $heure;
    protected int $id_praticien;
    protected int $id_patient;

    public function __construct(string $date, string $heure, int $id_praticien, int $id_patient) {
        $this->date = $date;
        $this->heure = $heure;
        $this->id_praticien = $id_praticien;
        $this->id_patient = $id_patient;
    }

}