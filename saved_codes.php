<?php
session_start();
include "db_connection.php"; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(["message" => "User not logged in"]);
        exit();
    }

    $user_id = $_SESSION['user_id']; // Get logged-in user ID
    $filename = isset($_POST['filename']) ? trim($_POST['filename']) : null;
    $code = isset($_POST['code']) ? trim($_POST['code']) : null;

    if (empty($filename) || empty($code)) {
        echo json_encode(["message" => "Filename and code cannot be empty"]);
        exit();
    }

    // Insert code into the database
    $stmt = $conn->prepare("INSERT INTO saved_codes (user_id, filename, code) VALUES (?, ?, ?)");
    
    if ($stmt === false) {
        echo json_encode(["message" => "Database error: " . $conn->error]);
        exit();
    }

    $stmt->bind_param("iss", $user_id, $filename, $code);

    if ($stmt->execute()) {
        echo json_encode(["message" => "Code saved successfully!", "filename" => $filename]);
    } else {
        echo json_encode(["message" => "Error saving code"]);
    }

    $stmt->close();
    $conn->close();
}
?>
