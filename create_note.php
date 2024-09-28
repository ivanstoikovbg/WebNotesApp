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
if (!isset($_SESSION['username'])) {
    echo json_encode(['message' => 'Грешка: Не е намерено потребителско име в сесията.']);
    exit;
}

$username = $_SESSION['username'];

$title = $_POST['title'];
$content = $_POST['content'];
$priority = $_POST["priority"];
$link = $_POST["link"]; 

$filename = "notes/note_" . uniqid() . ".html";

$stmt = $conn->prepare("INSERT INTO notes (title, content, username, priority, share_link, link) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssss", $title, $content, $username, $priority, $filename, $link);

if ($stmt->execute()) {
    echo json_encode(['message' => 'Бележката беше успешно създадена.', 'shareLink' => $filename]);
} else {
    echo json_encode(['message' => 'Грешка при създаване на бележка: ' . $stmt->error]);
}

$file = fopen($filename, "w");
fwrite($file, "<!DOCTYPE html>\n");
fwrite($file, "<html lang='en'>\n");
fwrite($file, "<head>\n");
fwrite($file, "    <meta charset='UTF-8'>\n");
fwrite($file, "    <meta name='viewport' content='width=device-width, initial-scale=1.0'>\n");
fwrite($file, "    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css'>\n");
fwrite($file, "    <link rel='stylesheet' href='/assets/css/share_note.css'>");
fwrite($file, "    <title>Бележка </title>\n");
fwrite($file, "</head>\n");
fwrite($file, "<body class='flex items-center justify-center h-screen'>\n");
fwrite($file, "<div class='note-container bg-white rounded-lg overflow-hidden'>\n");
fwrite($file, "<div class='note-header'>\n");
fwrite($file, "<h1 class='note-title'>Споделена бележка:</h1>\n");
fwrite($file, "</div>\n");
fwrite($file, "<div class='note-body'>");
fwrite($file, "<p><strong>Заглавие:</strong> " . htmlspecialchars($title) . "</p>\n");
fwrite($file, "<p><strong>Съдържание:</strong> " . htmlspecialchars($content) . "</p>\n");
fwrite($file, "<p><strong>Приоритет:</strong> " . htmlspecialchars($priority) . "</p>\n");
fwrite($file, "</div>\n");
fwrite($file, "</div>\n");
fwrite($file, "</div>\n");
fwrite($file, "</div>\n");
fwrite($file, "</body>\n");
fclose($file);


$stmt->close();
$conn->close();
?>
