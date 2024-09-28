<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include_once "db_connection.php";

    $username = $_POST["username"];
    $password = $_POST["password"];

    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($connection, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $hashed_password = $row["password"];
        if (password_verify($password, $hashed_password)) {
            $_SESSION["username"] = $username;
            header("Location: index.php");
            exit();
        } else {
            $_SESSION["login_error"] = "Невалидно потребителско име или парола.";
            header("Location: login.php");
            exit();
        }
    } else {
        $_SESSION["login_error"] = "Невалидно потребителско име или парола.";
        header("Location: login.php");
        exit();
    }
    mysqli_close($connection);
}
?>
