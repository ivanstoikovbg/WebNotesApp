<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ivanstoikov_notes";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Грешка при връзка с базата данни: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $noteId = $data['id'];
    
    $sql = "DELETE FROM notes WHERE id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $noteId);
    if ($stmt->execute()) {
        $response = ['success' => true];
        echo json_encode($response);
    } else {
        $response = ['success' => false, 'message' => 'Грешка при изтриване на бележката.'];
        echo json_encode($response);
    }

    $stmt->close();
    $conn->close();
} else {
    http_response_code(400);
    $response = ['success' => false, 'message' => 'Невалидна заявка за изтриване.'];
    echo json_encode($response);
}
?>
