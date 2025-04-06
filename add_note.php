<?php
session_start();
require "db_connection.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user_id = $_SESSION['user_id']; 
    $title = $_POST["title"];
    $content = $_POST["content"];

    if (!empty($title) && !empty($content)) {
        $stmt = $conn->prepare("INSERT INTO cheat_notes (user_id, title, content) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $user_id, $title, $content);

        if ($stmt->execute()) {
            echo json_encode(["status" => "success"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to add note"]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Title and Content required"]);
    }
}
?>