<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ivanstoikov_notes";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

session_start();
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
} else {
    echo json_encode(['message' => 'Грешка: Не е намерено потребителско име в сесията.']);
    exit;
}

$sql = "SELECT * FROM notes WHERE username = '$username'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $notes = [];
    while ($row = $result->fetch_assoc()) {
        $notes[] = [
            'id' => $row['id'],
            'title' => $row['title'],
            'content' => $row['content'],
            'priority' => $row['priority'],
            'share_link' => $row['share_link'],
        ];
    }
    echo json_encode(['notes' => $notes]);
} else {
    echo json_encode(['message' => 'Няма създадени бележки от този потребител.']);
}

$conn->close();
?>
