<?php
session_start();
require "db_connection.php";

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "User not logged in"]);
    exit();
}

// Handle both GET and POST requests for flexibility
if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET["note_id"])) {
    $id = $_GET["note_id"];
} elseif ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["id"])) {
    $id = $_POST["id"];
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request"]);
    exit();
}

// Ensure the note belongs to the logged-in user (security measure)
$user_id = $_SESSION['user_id'];
$check_stmt = $conn->prepare("SELECT id FROM cheat_notes WHERE id = ? AND user_id = ?");
$check_stmt->bind_param("ii", $id, $user_id);
$check_stmt->execute();
$result = $check_stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(["status" => "error", "message" => "Note not found or unauthorized"]);
    exit();
}

// Delete the note
$stmt = $conn->prepare("DELETE FROM cheat_notes WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $id, $user_id);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Note deleted successfully"]);
} else {
    echo json_encode(["status" => "error", "message" => "Failed to delete note: " . $conn->error]);
}

$stmt->close();
$conn->close();
?>