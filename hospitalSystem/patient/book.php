<?php

session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "patient") {
    header("Location: ../login.php");
    exit();
}

include "../config/database.php";
include "../classes/Appointment.php";

$doctor_id = $_GET["doctor_id"] ?? "";
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $doctor_id = $_POST["doctor_id"];
    $date = $_POST["appointment_date"];
    $time = $_POST["appointment_time"];
    $patient_id = $_SESSION["user_id"];

    // Check if the time is already booked
    $check = $conn->prepare(
        "SELECT id FROM appointments
         WHERE doctor_id = ?
         AND appointment_date = ?
         AND appointment_time = ?
         AND status = 'Booked'"
    );

    $check->bind_param("iss", $doctor_id, $date, $time);
    $check->execute();

    $result = $check->get_result();

    if ($result->num_rows > 0) {

        $message = "Sorry, this appointment time is already booked.";

    } else {

        $appointment = new Appointment(
            null,
            $patient_id,
            $doctor_id,
            $date,
            $time,
            "Booked"
        );

        $stmt = $conn->prepare(
            "INSERT INTO appointments
            (patient_id, doctor_id, appointment_date, appointment_time, status, created_at)
            VALUES (?, ?, ?, ?, ?, NOW())"
        );

        $stmt->bind_param(
            "iisss",
            $appointment->patient_id,
            $appointment->doctor_id,
            $appointment->appointment_date,
            $appointment->appointment_time,
            $appointment->status
        );

        if ($stmt->execute()) {
            $message = "Appointment booked successfully!";
        } else {
            $message = "Booking failed.";
        }
    }
}

?>

<!DOCTYPE html>
<html>
<head>

    <title>Book Appointment</title>

</head>

<body>

<h2>Book Appointment</h2>

<p><?php echo $message; ?></p>

<form method="POST">

    <input type="hidden" name="doctor_id" id="doctor_id"
           value="<?php echo htmlspecialchars($doctor_id); ?>">

    <label>Doctor ID:</label><br>

    <input type="text"
           value="<?php echo htmlspecialchars($doctor_id); ?>"
           readonly>

    <br><br>

    <label>Appointment Date:</label><br>

    <input type="date"
           name="appointment_date"
           id="appointment_date"
           required>

    <br><br>

    <label>Available Time:</label><br>

    <select name="appointment_time" id="appointment_time" required>

        <option value="">Select a date first</option>

    </select>

    <br><br>

    <button type="submit">Book Appointment</button>

</form>

<br>

<a href="doctors.php">Back to Doctors</a>

<script>

const doctorId = document.getElementById("doctor_id");
const dateInput = document.getElementById("appointment_date");
const timeSelect = document.getElementById("appointment_time");

dateInput.addEventListener("change", function () {

    const doctor = doctorId.value;
    const date = dateInput.value;

    if (doctor === "" || date === "") {
        return;
    }

    timeSelect.innerHTML = "<option>Checking availability...</option>";

    fetch("../api/availability.php?doctor_id=" + doctor + "&date=" + date)
        .then(response => response.json())
        .then(times => {

            timeSelect.innerHTML = "";

            if (times.length === 0) {

                timeSelect.innerHTML =
                    "<option value=''>No available times</option>";

            } else {

                times.forEach(function(time) {

                    const option = document.createElement("option");

                    option.value = time;
                    option.textContent = time;

                    timeSelect.appendChild(option);

                });

            }

        })
        .catch(error => {

            timeSelect.innerHTML =
                "<option value=''>Error checking availability</option>";

        });

});

</script>

</body>
</html>