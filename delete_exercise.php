<?php
session_start();
include 'db_connection.php';

if (!isset($_SESSION['user_id'])) {
    echo "Unauthorized access";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['id'])) {
    $id = $_POST['id'];
    $user_id = $_SESSION['user_id'];

    $query = "DELETE FROM saved_codes2 WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $id, $user_id);

    if ($stmt->execute()) {
        echo "Exercise deleted successfully!";
    } else {
        echo "Error deleting exercise.";
    }
    $stmt->close();
}
?>
