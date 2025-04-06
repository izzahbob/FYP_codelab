<?php
session_start();
include "db_connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if user is logged in
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(["message" => "User not logged in"]);
        exit();
    }

    // Get user_id from session
    $user_id = $_SESSION['user_id'];
    
    // Get raw POST data and decode JSON
    $data = json_decode(file_get_contents("php://input"), true);
    
    // Check if 'code' is set and not empty
    if (!isset($data['code']) || empty($data['code'])) {
        echo json_encode(["message" => "Code cannot be empty"]);
        exit();
    }
    
    $code = $data['code'];

    // Check if 'filename' is set, otherwise set default value
    if (!isset($data['filename']) || empty($data['filename'])) {
        $filename = "exercise_1"; // Default filename
    } else {
        $filename = $data['filename'];
    }

    // Prepare SQL statement to insert code into database
    $stmt = $conn->prepare("INSERT INTO saved_codes (user_id, filename, code) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $user_id, $filename, $code);

    if ($stmt->execute()) {
        echo json_encode(["message" => "Code saved successfully"]);
    } else {
        echo json_encode(["message" => "Error saving code"]);
    }

    $stmt->close();
    $conn->close();
}
?>
