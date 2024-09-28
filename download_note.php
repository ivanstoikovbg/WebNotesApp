<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ivanstoikov_notes";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    $noteId = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM notes WHERE id = ?");
    $stmt->bind_param("i", $noteId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row) {
        $noteTitle = $row['title'];
        $noteContent = $row['content'];

        $textData = "Заглавие:\n$noteTitle\n";
        $textData .= "Съдържание:\n$noteContent";

        header('Content-Type: text/plain');
        header('Content-Disposition: attachment; filename=' . urlencode($noteTitle) . '.txt');

        echo $textData;
    } else {
        echo 'Бележката не беше намерена.';
    }
} else {
    echo 'Не е предоставено ID на бележката.';
}
?>
