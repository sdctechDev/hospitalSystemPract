<?php

include "../config/database.php";

$patients = $conn->query("SELECT COUNT(*) AS total FROM patients")->fetch_assoc();
$doctors = $conn->query("SELECT COUNT(*) AS total FROM doctors")->fetch_assoc();
$appointments = $conn->query("SELECT COUNT(*) AS total FROM appointments")->fetch_assoc();
$payments = $conn->query("SELECT COUNT(*) AS total FROM payments")->fetch_assoc();

?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
</head>

<body>

<h2>Administrator Dashboard</h2>

<h3>System Summary</h3>

<p>Total Patients: <?php echo $patients["total"]; ?></p>

<p>Total Doctors: <?php echo $doctors["total"]; ?></p>

<p>Total Appointments: <?php echo $appointments["total"]; ?></p>

<p>Total Payments: <?php echo $payments["total"]; ?></p>

<h3>Appointments</h3>

<table border="1" cellpadding="10">

    <tr>
        <th>Patient</th>
        <th>Doctor</th>
        <th>Date</th>
        <th>Time</th>
        <th>Status</th>
    </tr>

    <?php

    $result = $conn->query(
        "SELECT appointments.*,
                patients.name AS patient_name,
                doctors.name AS doctor_name
         FROM appointments
         JOIN patients ON appointments.patient_id = patients.id
         JOIN doctors ON appointments.doctor_id = doctors.id
         ORDER BY appointment_date, appointment_time"
    );

    while ($appointment = $result->fetch_assoc()) {

    ?>

        <tr>

            <td><?php echo $appointment["patient_name"]; ?></td>

            <td><?php echo $appointment["doctor_name"]; ?></td>

            <td><?php echo $appointment["appointment_date"]; ?></td>

            <td><?php echo $appointment["appointment_time"]; ?></td>

            <td><?php echo $appointment["status"]; ?></td>

        </tr>

    <?php } ?>

</table>

</body>
</html>