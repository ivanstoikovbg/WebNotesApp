<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include_once "db_connection.php";

    $username = $_POST["username"];
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    $check_sql = "SELECT * FROM users WHERE username = ?";
    $check_stmt = mysqli_prepare($connection, $check_sql);
    
    if ($check_stmt) {
        mysqli_stmt_bind_param($check_stmt, "s", $username);
        mysqli_stmt_execute($check_stmt);
        mysqli_stmt_store_result($check_stmt);

        if (mysqli_stmt_num_rows($check_stmt) > 0) {
            echo "Потребител с това име вече съществува.";
            exit();
        }
        mysqli_stmt_close($check_stmt);
    } else {
        echo "Грешка при подготовка на заявката за проверка: " . mysqli_error($connection);
        exit();
    }

    if ($password === $confirm_password) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $insert_sql = "INSERT INTO users (username, password) VALUES (?, ?)";
        $insert_stmt = mysqli_prepare($connection, $insert_sql);

        if ($insert_stmt) {
            mysqli_stmt_bind_param($insert_stmt, "ss", $username, $hashed_password);
            $result = mysqli_stmt_execute($insert_stmt);

            if ($result) {
                header("Location: login.php");
                exit();
            } else {
                echo "Грешка при регистрация: " . mysqli_stmt_error($insert_stmt);
            }
            mysqli_stmt_close($insert_stmt);
        } else {
            echo "Грешка при подготовка на заявката за вмъкване: " . mysqli_error($connection);
        }
    } else {
        echo "Паролата не съвпада.";
    }
    mysqli_close($connection);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 h-screen flex items-center justify-center">
    <div class="w-full max-w-md shadow-lg bg-white rounded-md p-8 mt-8">
        <h2 class="text-4xl font-extrabold mb-6 text-center text-purple-600">Създай своя акаунт</h2>
        <form action="registration.php" method="post">
            <div class="mb-4">
                <label for="username" class="block text-lg font-semibold text-gray-700 mb-2">Потребителско име:</label>
                <input type="text" name="username" id="username" class="mt-1 p-3 w-full border border-purple-300 rounded-md focus:outline-none focus:border-purple-500" required>
            </div>
            <div class="mb-4">
                <label for="password" class="block text-lg font-semibold text-gray-700 mb-2">Парола:</label>
                <div class="relative">
                    <input type="password" name="password" id="password" class="mt-1 p-3 w-full border border-purple-300 rounded-md focus:outline-none focus:border-purple-500" required>
                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center">
                        <span class="ml-2 text-sm text-gray-600 cursor-pointer" onclick="togglePasswordVisibility('password', 'togglePasswordIcon')">
                            <i id="togglePasswordIcon" class="far fa-eye text-lg"></i>
                        </span>
                    </div>
                </div>
            </div>
            <div class="mb-4">
                <label for="confirm_password" class="block text-lg font-semibold text-gray-700 mb-2">Повтори паролата:</label>
                <div class="relative">
                    <input type="password" name="confirm_password" id="confirm_password" class="mt-1 p-3 w-full border border-purple-300 rounded-md focus:outline-none focus:border-purple-500" required>
                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center">
                        <span class="ml-2 text-sm text-gray-600 cursor-pointer" onclick="togglePasswordVisibility('confirm_password', 'toggleConfirmPasswordIcon')">
                            <i id="toggleConfirmPasswordIcon" class="far fa-eye text-lg"></i>
                        </span>
                    </div>
                </div>
            </div>
            <div class="flex items-center justify-center">
                <button type="submit" class="bg-purple-500 text-white py-3 px-9 rounded-md hover:bg-purple-600 focus:outline-none focus:shadow-outline-purple">Регистрирайте се</button>
            </div>
        </form>
        <p class="mt-4 text-sm text-gray-700 text-center">
            Вече имате акаунт? 
            <a href="login.php" class="text-purple-500 hover:underline text-sm font-bold">Влезте в профила си</a>.
        </p>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>
    <script>
        function togglePasswordVisibility(inputId, iconId) {
            var passwordInput = document.getElementById(inputId);
            var passwordIcon = document.getElementById(iconId);
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








