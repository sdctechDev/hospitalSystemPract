<?php

include "config/database.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = $_POST["email"];
    $password = $_POST["password"];
    $role = $_POST["role"];

    if ($role == "patient") {

        $stmt = $conn->prepare("SELECT * FROM patients WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows == 1) {

            $user = $result->fetch_assoc();

            if (password_verify($password, $user["password"])) {

                session_start();

                $_SESSION["user_id"] = $user["id"];
                $_SESSION["user_name"] = $user["name"];
                $_SESSION["role"] = "patient";

                header("Location: patient/dashboard.php");
                exit();

            } else {
                $message = "Invalid password.";
            }

        } else {
            $message = "Patient account not found.";
        }

    } elseif ($role == "doctor") {

        $stmt = $conn->prepare("SELECT * FROM doctors WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows == 1) {

            $user = $result->fetch_assoc();

            if ($password == $user["password"]) {

                session_start();

                $_SESSION["user_id"] = $user["id"];
                $_SESSION["user_name"] = $user["name"];
                $_SESSION["role"] = "doctor";

                header("Location: doctor/dashboard.php");
                exit();

            } else {
                $message = "Invalid password.";
            }

        } else {
            $message = "Doctor account not found.";
        }
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Hospital Login</title>
</head>

<body>

<h2>Hospital System Login</h2>

<p><?php echo $message; ?></p>

<form method="POST">

    <label>Email:</label><br>
    <input type="email" name="email" required><br><br>

    <label>Password:</label><br>
    <input type="password" name="password" required><br><br>

    <label>Login as:</label><br>

    <select name="role" required>
        <option value="patient">Patient</option>
        <option value="doctor">Doctor</option>
    </select>

    <br><br>

    <button type="submit">Login</button>

</form>

<br>

<a href="register.php">Register as a patient</a>

</body>
</html>