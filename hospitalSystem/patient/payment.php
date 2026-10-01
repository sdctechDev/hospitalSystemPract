<?php

session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "patient") {
    header("Location: ../login.php");
    exit();
}

include "../config/database.php";
include "../classes/Payment.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $appointment_id = $_POST["appointment_id"];
    $payment_method = $_POST["payment_method"];

    // Get appointment and consultation fee
    $stmt = $conn->prepare(
        "SELECT appointments.id, doctors.consultation_fee
         FROM appointments
         JOIN doctors ON appointments.doctor_id = doctors.id
         WHERE appointments.id = ?
         AND appointments.patient_id = ?"
    );

    $stmt->bind_param("ii", $appointment_id, $_SESSION["user_id"]);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows == 1) {

        $appointment = $result->fetch_assoc();

        $amount = $appointment["consultation_fee"];

        $reference = "PAY" . rand(10000, 99999);

        $payment = new Payment(
            null,
            $appointment_id,
            $amount,
            $reference,
            "Paid",
            $payment_method
        );

        $insert = $conn->prepare(
            "INSERT INTO payments
            (appointment_id, amount, payment_reference, payment_status, payment_method, created_at)
            VALUES (?, ?, ?, ?, ?, NOW())"
        );

        $insert->bind_param(
            "idsss",
            $payment->appointment_id,
            $payment->amount,
            $payment->payment_reference,
            $payment->payment_status,
            $payment->payment_method
        );

        if ($insert->execute()) {
            $message = "Payment successful! Reference: " . $reference;
        } else {
            $message = "Payment failed.";
        }

    } else {

        $message = "Appointment not found.";
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Make Payment</title>
</head>

<body>

<h2>Make Payment</h2>

<p><?php echo $message; ?></p>

<form method="POST">

    <label>Appointment ID:</label><br>
    <input type="number" name="appointment_id" required>

    <br><br>

    <label>Payment Method:</label><br>

    <select name="payment_method" required>

        <option value="">Select Payment Method</option>
        <option value="Card">Card</option>
        <option value="Bank Transfer">Bank Transfer</option>
        <option value="Cash">Cash</option>

    </select>

    <br><br>

    <button type="submit">Make Payment</button>

</form>

<br>

<a href="dashboard.php">Back to Dashboard</a>

</body>
</html>