<?php

include "config/database.php";
include "classes/Patient.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = $_POST["name"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

    $check = $conn->prepare("SELECT id FROM patients WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        $message = "Email already exists.";
    } else {

        $stmt = $conn->prepare(
            "INSERT INTO patients (name, email, phone, password, created_at)
             VALUES (?, ?, ?, ?, NOW())"
        );

        $stmt->bind_param("ssss", $name, $email, $phone, $password);

        if ($stmt->execute()) {
            $message = "Registration successful! You can now login.";
        } else {
            $message = "Registration failed.";
        }
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Patient Registration</title>
    <link rel="stylesheet" href="#">
</head>
<body>

<h2>Patient Registration</h2>

<p><?php echo $message; ?></p>

<form method="POST">

    <label>Name:</label><br>
    <input type="text" name="name" required><br><br>

    <label>Email:</label><br>
    <input type="email" name="email" placeholder="danerry30@gmail.com" required><br><br>

    <label>Phone:</label><br>
    <input type="text" name="phone" required><br><br>

    <label>Password:</label><br>
    <input type="password" name="password" required><br><br>

    <button type="submit">Register</button>

</form>

<br>

<a href="login.php">Already have an account? Login</a>

</body>
</html>