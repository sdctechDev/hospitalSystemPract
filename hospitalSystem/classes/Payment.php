<?php

class Payment
{
    public $id;
    public $appointment_id;
    public $amount;
    public $payment_reference;
    public $payment_status;
    public $payment_method;

    public function __construct($id, $appointment_id, $amount, $payment_reference, $payment_status, $payment_method)
    {
        $this->id = $id;
        $this->appointment_id = $appointment_id;
        $this->amount = $amount;
        $this->payment_reference = $payment_reference;
        $this->payment_status = $payment_status;
        $this->payment_method = $payment_method;
    }
}

?>