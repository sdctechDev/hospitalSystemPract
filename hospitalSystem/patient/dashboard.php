<?php

session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "patient") {
    header("Location: ../login.php");
    exit();
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Patient Dashboard</title>
</head>

<body>

<h2>Patient Dashboard</h2>

<p>Welcome, <?php echo $_SESSION["user_name"]; ?>!</p>

<h3>Patient Menu</h3>

<ul>
    <li><a href="doctors.php">View Doctors</a></li>
    <li><a href="book.php">Book Appointment</a></li>
    <li><a href="payment.php">Make Payment</a></li>
</ul>

<a href="../login.php">Logout</a>

</body>
</html>