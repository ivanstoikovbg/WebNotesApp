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
            echo "Невалидно потребителско име или парола.";
        }
    } else {
        echo "Невалидно потребителско име или парола.";
    }
    mysqli_close($connection);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 h-screen flex items-center justify-center">
    <div class="w-full max-w-md shadow-lg bg-white rounded-md p-8 mt-8">
        <h2 class="text-4xl font-extrabold mb-6 text-center text-purple-600">Вход</h2>
        <form action="check_login.php" method="post">
            <div class="mb-4">
                <label for="username" class="block text-lg font-semibold text-gray-700 mb-2">Потребителско име:</label>
                <input type="text" name="username" id="username" class="mt-1 p-3 w-full border border-purple-300 rounded-md focus:outline-none focus:border-purple-500" required>
            </div>
            <div class="mb-4">
                <label for="password" class="block text-lg font-semibold text-gray-700 mb-2">Парола:</label>
                <div class="relative">
                    <input type="password" name="password" id="password" class="mt-1 p-3 w-full border border-purple-300 rounded-md focus:outline-none focus:border-purple-500" required>
                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center">
                        <span class="ml-2 text-sm text-gray-600 cursor-pointer" onclick="togglePasswordVisibility()">
                            <i id="togglePasswordIcon" class="far fa-eye text-lg"></i>
                        </span>
                    </div>
                </div>
            </div>
            <div class="flex items-center justify-center">
                <button type="submit" class="bg-purple-500 text-white py-3 px-9 rounded-md hover:bg-purple-600 focus:outline-none focus:shadow-outline-purple">Влез</button>
            </div>
        </form>
        <p class="mt-4 text-sm text-gray-700 text-center">
            Нямате акаунт? 
            <a href="registration.php" class="text-purple-500 hover:underline text-sm font-bold">Регистрирайте се тук</a>.
        </p>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>
    <script>
        function togglePasswordVisibility() {
            var passwordInput = document.getElementById("password");
            var passwordIcon = document.getElementById("togglePasswordIcon");
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                passwordIcon.classList.remove("far", "fa-eye");
                passwordIcon.classList.add("far", "fa-eye-slash");
            } else {
                passwordInput.type = "password";
                passwordIcon.classList.remove("far", "fa-eye-slash");
                passwordIcon.classList.add("far", "fa-eye");
            }
        }
    </script>
</body>
</html>



