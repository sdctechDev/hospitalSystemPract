<?php

class Doctor
{
    public $id;
    public $name;
    public $email;
    public $phone;
    public $specialization_id;
    public $consultation_fee;

    public function __construct($id, $name, $email, $phone, $specialization_id, $consultation_fee)
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->phone = $phone;
        $this->specialization_id = $specialization_id;
        $this->consultation_fee = $consultation_fee;
    }
}

?>