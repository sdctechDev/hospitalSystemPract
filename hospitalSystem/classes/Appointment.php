<?php

class Appointment
{
    public $id;
    public $patient_id;
    public $doctor_id;
    public $appointment_date;
    public $appointment_time;
    public $status;

    public function __construct($id, $patient_id, $doctor_id, $appointment_date, $appointment_time, $status)
    {
        $this->id = $id;
        $this->patient_id = $patient_id;
        $this->doctor_id = $doctor_id;
        $this->appointment_date = $appointment_date;
        $this->appointment_time = $appointment_time;
        $this->status = $status;
    }
}

?>