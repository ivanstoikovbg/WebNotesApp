<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ivanstoikov_notes";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$id = isset($_GET['id']) ? $_GET['id'] : null;
if (ctype_alnum($id) && strlen($id) === 13) {
    $sql = "SELECT * FROM notes WHERE share_link = '$id'";
} else {
    $sql = "SELECT * FROM notes WHERE id = '$id'";
}

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $response = ['title' => $row['title'], 'content' => $row['content'], 'priority' => $row['priority']];
    echo json_encode($response);
} else {
    echo json_encode(['message' => 'Грешка: Не е намерена бележка с този идентификатор.']);
}

$conn->close();
?>
