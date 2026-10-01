<?php

include "../config/database.php";

header("Content-Type: application/json");

$doctor_id = $_GET["doctor_id"];
$date = $_GET["date"];

$all_times = [
    "09:00:00",
    "10:00:00",
    "11:00:00",
    "12:00:00",
    "13:00:00",
    "14:00:00",
    "15:00:00",
    "16:00:00"
];

$stmt = $conn->prepare(
    "SELECT appointment_time
     FROM appointments
     WHERE doctor_id = ?
     AND appointment_date = ?
     AND status = 'Booked'"
);

$stmt->bind_param("is", $doctor_id, $date);
$stmt->execute();

$result = $stmt->get_result();

$booked_times = [];

while ($row = $result->fetch_assoc()) {
    $booked_times[] = $row["appointment_time"];
}

$available_times = [];

foreach ($all_times as $time) {
    if (!in_array($time, $booked_times)) {
        $available_times[] = $time;
    }
}

echo json_encode($available_times);

?>