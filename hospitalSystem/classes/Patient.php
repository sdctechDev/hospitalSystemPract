<?php

class Patient
{
    public $id;
    public $name;
    public $email;
    public $phone;

    public function __construct($id, $name, $email, $phone)
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->phone = $phone;
    }
}

?>