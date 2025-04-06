<?php
session_start();
include 'db_connection.php'; 

if (!isset($_SESSION['user_id'])) {
    echo "Unauthorized access!";
    exit();
}

$user_id = $_SESSION['user_id'];
$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password']; // This may be empty

// Check if password is provided
if (!empty($password)) {
    // Hash the new password before updating
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $query = "UPDATE users SET username = ?, email = ?, password = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssi", $username, $email, $hashed_password, $user_id);
} else {
    // Update without changing the password
    $query = "UPDATE users SET username = ?, email = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssi", $username, $email, $user_id);
}

if ($stmt->execute()) {
    echo "Profile updated successfully!";
} else {
    echo "Error updating profile.";
}

$stmt->close();
$conn->close();
?>
