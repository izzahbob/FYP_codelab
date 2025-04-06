<?php
session_start();
require "db_connection.php";

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode([]);
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user notes
$notes = [];
$notes_query = $conn->query("SELECT * FROM cheat_notes WHERE user_id = $user_id ORDER BY created_at DESC");

if ($notes_query->num_rows > 0) {
    $notes = $notes_query->fetch_all(MYSQLI_ASSOC);
}

// Return notes as JSON
echo json_encode($notes);
?>