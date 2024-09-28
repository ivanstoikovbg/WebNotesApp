<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ivanstoikov_notes";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$data = json_decode(file_get_contents("php://input"), true);

$noteId = $data['id'];
$newTitle = $data['title'];
$newContent = $data['content'];
$newPriority = $data['priority'];

$filename = "notes/note_" . uniqid() . ".html";
$sql = "UPDATE notes SET title = ?, content = ?, share_link = ?, priority = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssi", $newTitle, $newContent, $filename, $newPriority, $noteId);

if ($stmt->execute()) {
    echo json_encode(['message' => 'Бележката беше успешно създадена.', 'shareLink' => $uuid]);
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
fwrite($file, "<p><strong>Заглавие:</strong> " . htmlspecialchars($newTitle) . "</p>\n");
fwrite($file, "<p><strong>Съдържание:</strong> " . htmlspecialchars($newContent) . "</p>\n");
fwrite($file, "<p><strong>Приоритет:</strong> " . htmlspecialchars($newPriority) . "</p>\n");
fwrite($file, "</div>\n");
fwrite($file, "</div>\n");
fwrite($file, "</div>\n");
fwrite($file, "</div>\n");
fwrite($file, "</body>\n");
fclose($file);

$stmt->close();
$conn->close();
?>