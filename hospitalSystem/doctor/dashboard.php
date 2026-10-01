<?php

session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "doctor") {
    header("Location: ../login.php");
    exit();
}

include "../config/database.php";

$doctor_id = $_SESSION["user_id"];

$stmt = $conn->prepare(
    "SELECT appointments.*, patients.name AS patient_name
     FROM appointments
     JOIN patients ON appointments.patient_id = patients.id
     WHERE appointments.doctor_id = ?
     ORDER BY appointment_date, appointment_time"
);

$stmt->bind_param("i", $doctor_id);
$stmt->execute();

$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html>
<head>
    <title>Doctor Dashboard</title>
</head>

<body>

<h2>Doctor Dashboard</h2>

<p>Welcome, <?php echo $_SESSION["user_name"]; ?>!</p>

<h3>My Appointments</h3>

<table border="1" cellpadding="10">

    <tr>
        <th>Patient</th>
        <th>Date</th>
        <th>Time</th>
        <th>Status</th>
    </tr>

    <?php while ($appointment = $result->fetch_assoc()) { ?>

        <tr>

            <td><?php echo $appointment["patient_name"]; ?></td>

            <td><?php echo $appointment["appointment_date"]; ?></td>

            <td><?php echo $appointment["appointment_time"]; ?></td>

            <td><?php echo $appointment["status"]; ?></td>

        </tr>

    <?php } ?>

</table>

<br>

<a href="../login.php">Logout</a>

</body>
</html>