<?php
session_start();
include("db_connection.php");

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["message" => "User not logged in"]);
    exit;
}

$user_id = $_SESSION['user_id']; // Fetch user ID from session
$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['filename'], $data['code'])) {
    $filename = trim($data['filename']);
    $code = trim($data['code']);

    if (!empty($filename) && !empty($code)) {
        $stmt = $conn->prepare("INSERT INTO saved_codes2 (user_id, filename, code) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $user_id, $filename, $code);

        if ($stmt->execute()) {
            echo json_encode(["message" => "Code saved successfully!"]);
        } else {
            echo json_encode(["message" => "Error saving code."]);
        }

        $stmt->close();
    } else {
        echo json_encode(["message" => "Filename and code cannot be empty."]);
    }
} else {
    echo json_encode(["message" => "Invalid request."]);
}

$conn->close();
?>
