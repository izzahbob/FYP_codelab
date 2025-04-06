<?php
include 'db_connection.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Step 1: Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $content = $_POST['content'];

    $stmt = $conn->prepare("UPDATE cheat_notes SET title = ?, content = ? WHERE id = ?");
    $stmt->bind_param("ssi", $title, $content, $id);

    if ($stmt->execute()) {
        echo "Note updated successfully. <a href='index.php'>Go back</a>";
    } else {
        echo "Error updating note.";
    }

    exit();
}

// Step 2: Fetch note data for editing
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $conn->prepare("SELECT * FROM cheat_notes WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $note = $result->fetch_assoc();
    } else {
        echo "Note not found.";
        exit();
    }
} else {
    echo "No ID provided.";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Note</title>
</head>
<body>
    <h1>Edit Note</h1>
    <form method="post" action="edit_note.php">
        <input type="hidden" name="id" value="<?= $note['id'] ?>">
        <label>Title:</label><br>
        <input type="text" name="title" value="<?= htmlspecialchars($note['title']) ?>"><br><br>

        <label>Content:</label><br>
        <textarea name="content" rows="10" cols="50"><?= htmlspecialchars($note['content']) ?></textarea><br><br>

        <button type="submit">Save Changes</button>
    </form>
</body>
</html>
