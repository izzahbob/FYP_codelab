<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Database connection
include 'db_connection.php';

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process edit note form submission via AJAX
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['action']) && $_POST['action'] === 'edit_note') {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $content = $_POST['content'];

    $stmt = $conn->prepare("UPDATE cheat_notes SET title = ?, content = ? WHERE id = ?");
    $stmt->bind_param("ssi", $title, $content, $id);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Note updated successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error updating note: ' . $conn->error]);
    }
    exit();
}

// Fetch user data
$user_id = $_SESSION['user_id'];
$result = $conn->query("SELECT * FROM users WHERE id = $user_id");

if ($result->num_rows == 1) {
    $user = $result->fetch_assoc();
    $username = $user['username'];
} else {
    session_destroy();
    header("Location: login.php");
    exit();
}

// Fetch user notes
$notes = [];
$note_result = $conn->query("SELECT * FROM cheat_notes WHERE user_id = $user_id");

if ($note_result->num_rows > 0) {
    while ($row = $note_result->fetch_assoc()) {
        $notes[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Java Cheat Sheet | Codelab</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --color-bg: #e2dfcf;
            --color-dark: #36332d;
            --color-medium: #6d685f;
            --color-accent: #a3b45c;
            --font-main: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            --shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            --radius: 8px;
            --transition: all 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: var(--font-main);
            background-color: var(--color-bg);
            color: var(--color-dark);
            line-height: 1.6;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Navigation Section */
        .navbar {
            background-color: var(--color-dark);
            padding: 15px 0;
            box-shadow: var(--shadow);
        }

        .nav-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .logo {
            font-size: 24px;
            font-weight: bold;
            color: white;
            text-decoration: none;
        }

        .nav-links {
            display: flex;
            list-style: none;
        }

        .nav-links li {
            margin-left: 30px;
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            font-size: 16px;
            font-weight: 500;
            position: relative;
            padding-bottom: 5px;
            transition: var(--transition);
        }

        .nav-links a:hover {
            color: var(--color-accent);
        }

        /* Add underline animation to nav links */
        .nav-links a::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: 0;
            left: 0;
            background-color: var(--color-accent);
            transition: width 0.3s ease;
        }

        .nav-links a:hover::after {
            width: 100%;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            padding: 40px 0;
            max-width: 1200px;
            margin: 0 auto;
            width: 100%;
        }

        .page-title {
            text-align: center;
            font-size: 36px;
            margin-bottom: 40px;
            color: var(--color-dark);
            position: relative;
        }

        .page-title::after {
            content: '';
            display: block;
            width: 100px;
            height: 4px;
            background-color: var(--color-accent);
            margin: 15px auto 0;
            border-radius: 2px;
        }

        /* Container design */
        .container {
            background: white;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            padding: 30px;
            margin: 0 20px;
        }

        /* Note form styling */
        .note-form {
            background-color: var(--color-dark);
            padding: 30px;
            border-radius: var(--radius);
            margin-bottom: 40px;
            box-shadow: var(--shadow);
            position: relative;
            overflow: hidden;
        }

        .note-form::before {
            content: '';
            position: absolute;
            top: -50px;
            right: -50px;
            width: 100px;
            height: 100px;
            background-color: rgba(255, 255, 255, 0.05);
            border-radius: 50%;
        }

        .note-form h3 {
            color: white;
            margin-bottom: 20px;
            font-size: 24px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        input[type="text"], textarea {
            width: 100%;
            padding: 12px 15px;
            border: none;
            border-radius: var(--radius);
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
            font-family: var(--font-main);
            font-size: 16px;
            transition: var(--transition);
        }

        input[type="text"]:focus, textarea:focus {
            outline: none;
            background-color: rgba(255, 255, 255, 0.2);
            box-shadow: 0 0 0 2px var(--color-accent);
        }

        input[type="text"]::placeholder, textarea::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }

        textarea {
            min-height: 120px;
            resize: vertical;
        }

        .btn {
            padding: 12px 25px;
            border: none;
            border-radius: var(--radius);
            cursor: pointer;
            font-weight: 600;
            font-size: 16px;
            transition: var(--transition);
            display: inline-block;
            text-align: center;
            text-decoration: none;
        }

        .btn-primary {
            background-color: var(--color-accent);
            color: var(--color-dark);
        }

        .btn-primary:hover {
            background-color: #b3c46c;
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(163, 180, 92, 0.4);
        }

        .btn-secondary {
            background-color: var(--color-medium);
            color: white;
        }

        .btn-secondary:hover {
            background-color: #5d5a52;
            transform: translateY(-2px);
        }

        .btn-danger {
            background-color: #e74c3c;
            color: white;
        }

        .btn-danger:hover {
            background-color: #c0392b;
            transform: translateY(-2px);
        }

        /* Notes container */
        .notes-section {
            margin-top: 30px;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            border-bottom: 2px solid var(--color-accent);
            padding-bottom: 10px;
        }

        .section-header h2 {
            font-size: 24px;
            color: var(--color-dark);
        }

        .notes-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 25px;
        }

        .note-card {
            background-color: var(--color-dark);
            border-radius: var(--radius);
            overflow: hidden;
            box-shadow: var(--shadow);
            transition: var(--transition);
            position: relative;
            margin-bottom: 25px;
        }

        .note-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }

        .note-header {
            background-color: rgba(163, 180, 92, 0.9);
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .note-title {
            font-size: 18px;
            font-weight: bold;
            color: var(--color-dark);
            margin: 0;
        }

        .note-content {
            padding: 20px;
            color: white;
            font-size: 15px;
            line-height: 1.5;
            max-height: 200px;
            overflow-y: auto;
        }

        .note-actions {
            padding: 15px 20px;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            background-color: rgba(0, 0, 0, 0.1);
        }

        .note-btn {
            padding: 8px 15px;
            font-size: 14px;
            border-radius: var(--radius);
        }

        /* No notes message */
        .no-notes {
            text-align: center;
            padding: 40px 0;
            color: var(--color-medium);
            font-size: 18px;
        }

        .no-notes i {
            font-size: 50px;
            margin-bottom: 15px;
            opacity: 0.5;
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            z-index: 999;
            justify-content: center;
            align-items: center;
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .modal-content {
            background-color: white;
            border-radius: var(--radius);
            width: 90%;
            max-width: 600px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            animation: scaleIn 0.3s ease;
        }

        @keyframes scaleIn {
            from { transform: scale(0.9); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }

        .modal-header {
            padding: 20px;
            background-color: var(--color-dark);
            color: white;
            border-top-left-radius: var(--radius);
            border-top-right-radius: var(--radius);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-header h2 {
            margin: 0;
            font-size: 24px;
        }

        .close-button {
            background: none;
            border: none;
            color: white;
            font-size: 24px;
            cursor: pointer;
            transition: var(--transition);
        }

        .close-button:hover {
            color: var(--color-accent);
        }

        .modal-body {
            padding: 20px;
        }

        .modal-footer {
            padding: 15px 20px;
            border-top: 1px solid #eee;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }

        #edit-modal input[type="text"], 
        #edit-modal textarea {
            color: var(--color-dark);
            background-color: #f8f8f8;
            border: 1px solid #ddd;
        }

        /* Toast notification */
        .toast {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background-color: #333;
            color: white;
            padding: 15px 25px;
            border-radius: var(--radius);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            z-index: 1000;
            display: flex;
            align-items: center;
            gap: 12px;
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.3s ease, transform 0.3s ease;
        }

        .toast.success {
            background-color: #2ecc71;
        }

        .toast.error {
            background-color: #e74c3c;
        }

        .toast.show {
            opacity: 1;
            transform: translateY(0);
        }

        .toast i {
            font-size: 20px;
        }

        /* Footer */
        .footer {
            background-color: var(--color-dark);
            color: white;
            text-align: center;
            padding: 20px;
            margin-top: auto;
        }

        /* Edit and Delete buttons */
        .edit-btn, .delete-btn {
            padding: 8px 15px;
            border: none;
            border-radius: var(--radius);
            cursor: pointer;
            font-weight: 600;
            font-size: 14px;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .edit-btn {
            background-color: var(--color-medium);
            color: white;
        }

        .edit-btn:hover {
            background-color: #5d5a52;
            transform: translateY(-2px);
        }

        .delete-btn {
            background-color: #e74c3c;
            color: white;
        }

        .delete-btn:hover {
            background-color: #c0392b;
            transform: translateY(-2px);
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .notes-grid {
                grid-template-columns: 1fr;
            }
            
            .nav-container {
                flex-direction: column;
            }
            
            .nav-links {
                margin-top: 15px;
                justify-content: center;
            }
            
            .nav-links li {
                margin: 0 10px;
            }
            
            .page-title {
                font-size: 28px;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation Section --> 
    <nav class="navbar">
        <div class="nav-container">
            <a href="homepage.php" class="logo">Codelab</a>
            <ul class="nav-links">
                <li><a href="editor.php">Code Editor</a></li>
                <li><a href="exercise.html">Exercises</a></li>
                <li><a href="homepage.php">Home</a></li>
                <li><a href="profile.php">Profile</a></li>
            </ul>
        </div>
    </nav>

    <div class="main-content">
    <h1 class="page-title">Java Cheat Sheet</h1>
        <!-- Add Note Section --> 
        <div class="note-form">
            <h3><i class="fas fa-plus-circle"></i> Add New Note</h3>
            <form id="add-note-form">
                <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                <div class="form-group">
                    <input type="text" name="title" placeholder="Enter Title" required>
                </div>
                <div class="form-group">
                    <textarea name="content" placeholder="Enter Code or Notes" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Save Note
                </button>
            </form>
        </div>

        <!-- Note Section -->
        <div class="notes-container">
            <div class="section-header">
                <h2><i class="fas fa-book"></i> Your Notes</h2>
                <div class="notes-filter">
                    <span id="notes-count"><?php echo count($notes); ?> notes</span>
                </div>
            </div>

            <div class="notes-grid" id="notes-grid">
                <?php if (empty($notes)): ?>
                    <div class="no-notes">
                        <i class="fas fa-sticky-note"></i>
                        <p>No notes found. Start adding some!</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($notes as $note): ?>
                        <div class="note-card" data-id="<?php echo $note['id']; ?>">
                            <div class="note-header">
                                <h3 class="note-title"><?php echo htmlspecialchars($note['title']); ?></h3>
                            </div>
                            <div class="note-content">
                                <p><?php echo nl2br(htmlspecialchars($note['content'])); ?></p>
                            </div>
                            <div class="note-actions">
                                <button class="edit-btn" data-id="<?php echo $note['id']; ?>" 
                                        data-title="<?php echo htmlspecialchars($note['title']); ?>" 
                                        data-content="<?php echo htmlspecialchars($note['content']); ?>">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <button class="delete-btn" onclick="confirmDelete(<?php echo $note['id']; ?>)">
                                    <i class="fas fa-trash-alt"></i> Delete
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="edit-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2><i class="fas fa-edit"></i> Edit Note</h2>
                <button type="button" class="close-button" onclick="closeEditModal()">&times;</button>
            </div>
            <div class="modal-body">
                <form id="edit-note-form">
                    <input type="hidden" id="edit-id" name="id">
                    <input type="hidden" name="action" value="edit_note">
                    <div class="form-group">
                        <input type="text" id="edit-title" name="title" placeholder="Enter Title" required>
                    </div>
                    <div class="form-group">
                        <textarea id="edit-content" name="content" placeholder="Enter Content" required></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeEditModal()">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="saveEdit()">Save Changes</button>
            </div>
        </div>
    </div>

    <!-- Confirm Delete Modal -->
    <div id="delete-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2><i class="fas fa-exclamation-triangle"></i> Confirm Delete</h2>
                <button type="button" class="close-button" onclick="closeDeleteModal()">&times;</button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this note? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeDeleteModal()">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirm-delete-btn">Delete</button>
            </div>
        </div>
    </div>

    <!-- Toast notification -->
    <div id="toast" class="toast">
        <i class="fas fa-check-circle"></i>
        <span id="toast-message"></span>
    </div>

    <script>
        // Add Note Form Submit
        document.getElementById('add-note-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            fetch('add_note.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    showToast('Note successfully added!', 'success');
                    // Reset form
                    document.getElementById('add-note-form').reset();
                    // Reload notes
                    location.reload();
                } else {
                    showToast('Error: ' + data.message, 'error');
                }
            })
            .catch(error => {
                showToast('Error: Could not add note', 'error');
            });
        });

        // Open Edit Modal
        function openEditModal(id, title, content) {
            document.getElementById('edit-id').value = id;
            document.getElementById('edit-title').value = title;
            document.getElementById('edit-content').value = content.replace(/<br\s*\/?>/g, '\n');
            
            document.getElementById('edit-modal').style.display = 'flex';
            document.getElementById('edit-title').focus();
        }

        // Close Edit Modal
        function closeEditModal() {
            document.getElementById('edit-modal').style.display = 'none';
        }

        // Save Edit
        function saveEdit() {
            const form = document.getElementById('edit-note-form');
            const formData = new FormData(form);
            
            fetch('cheat.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    showToast('Note successfully updated!', 'success');
                    closeEditModal();
                    // Reload to show updated notes
                    location.reload();
                } else {
                    showToast('Error: ' + data.message, 'error');
                }
            })
            .catch(error => {
                showToast('Error: Could not update note', 'error');
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            const editButtons = document.querySelectorAll('.edit-btn');
            editButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    const title = this.getAttribute('data-title');
                    const content = this.getAttribute('data-content');
                    openEditModal(id, title, content);
                });
            });
        });

        // Confirm Delete
        function confirmDelete(id) {
            const deleteModal = document.getElementById('delete-modal');
            deleteModal.style.display = 'flex';
            
            document.getElementById('confirm-delete-btn').onclick = function() {
                deleteNote(id);
            };
        }

        // Close Delete Modal
        function closeDeleteModal() {
            document.getElementById('delete-modal').style.display = 'none';
        }

        // Delete Note
        function deleteNote(id) {
            fetch('delete_note.php?note_id=' + id)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.status === 'success') {
                    showToast('Note successfully deleted!', 'success');
                    closeDeleteModal();
                    
                    // Remove note from DOM
                    const noteElement = document.querySelector(`.note-card[data-id="${id}"]`);
                    if (noteElement) {
                        noteElement.remove();
                    }
                    
                    // Update notes count
                    updateNotesCount();
                    
                    // Check if no notes left
                    const notesGrid = document.getElementById('notes-grid');
                    if (notesGrid.querySelector('.note-card') === null) {
                        notesGrid.innerHTML = `
                            <div class="no-notes">
                                <i class="fas fa-sticky-note"></i>
                                <p>No notes found. Start adding some!</p>
                            </div>
                        `;
                    }
                } else {
                    showToast('Error: ' + data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Error: Could not delete note', 'error');
            });
        }

        // Update notes count
        function updateNotesCount() {
            const count = document.querySelectorAll('.note-card').length;
            document.getElementById('notes-count').textContent = `${count} note${count !== 1 ? 's' : ''}`;
        }

        // Show Toast
        function showToast(message, type = 'success') {
            const toast = document.getElementById('toast');
            const toastMessage = document.getElementById('toast-message');
            
            toast.className = 'toast';
            toast.classList.add(type);
            toast.classList.add('show');
            
            toastMessage.textContent = message;
            
            setTimeout(function() {
                toast.classList.remove('show');
            }, 3000);
        }

        // Close modal when clicking outside of it
        window.onclick = function(event) {
            const editModal = document.getElementById('edit-modal');
            const deleteModal = document.getElementById('delete-modal');
            
            if (event.target === editModal) {
                closeEditModal();
            }
            
            if (event.target === deleteModal) {
                closeDeleteModal();
            }
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            updateNotesCount();
        });
    </script>
</body>
</html>