<?php

include "../config/database.php";

$sql = "SELECT doctors.*, specializations.name AS specialization
        FROM doctors
        JOIN specializations
        ON doctors.specialization_id = specializations.id";

$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Doctors</title>
</head>

<body>

<h2>Available Doctors</h2>

<table border="1" cellpadding="10">

    <tr>
        <th>Doctor</th>
        <th>Specialization</th>
        <th>Consultation Fee</th>
        <th>Action</th>
    </tr>

    <?php while ($doctor = $result->fetch_assoc()) { ?>

        <tr>

            <td><?php echo $doctor["name"]; ?></td>

            <td><?php echo $doctor["specialization"]; ?></td>

            <td>₦<?php echo number_format($doctor["consultation_fee"], 2); ?></td>

            <td>
                <a href="book.php?doctor_id=<?php echo $doctor["id"]; ?>">
                    Book Appointment
                </a>
            </td>

        </tr>

    <?php } ?>

</table>

<br>

<a href="dashboard.php">Back to Dashboard</a>

</body>
</html>