<?php
session_start();
require "db_connection.php";

$user_id = $_SESSION['user_id']; 

$result = $conn->query("SELECT id, title, content FROM cheat_notes WHERE user_id = $user_id ORDER BY created_at DESC");
$notes = [];

while ($row = $result->fetch_assoc()) {
    $notes[] = $row;
}

echo json_encode($notes);
?>
