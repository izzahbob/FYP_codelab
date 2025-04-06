<?php
session_start();
include 'db_connection.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user details
$query = "SELECT email, username, password FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// Fetch saved code from code editor
$query_code = "SELECT id, filename, code FROM saved_codes WHERE user_id = ?";
$stmt = $conn->prepare($query_code);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$code_results = $stmt->get_result();
$stmt->close();

// Fetch saved exercises
$query_exercises = "SELECT id, filename, code FROM saved_codes2 WHERE user_id = ?";
$stmt = $conn->prepare($query_exercises);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$exercise_results = $stmt->get_result();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Profile - Codelab</title>
    <link rel="stylesheet" href="homepage.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-primary: #e0ddd3;
            --bg-secondary: #a09986;
            --bg-dark: #2e2a1c;
            --text-light: #ffffff;
            --accent-color: #8cbf5a;
            
            /* Additional modern color variables */
            --shadow-subtle: rgba(0, 0, 0, 0.08);
            --shadow-medium: rgba(0, 0, 0, 0.15);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            transition: all 0.3s ease;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
            background-color: var(--bg-primary);
            line-height: 1.6;
            color: var(--bg-dark);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

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

        .profile-container {
            flex-grow: 1;
            max-width: 1400px;
            margin: 2rem auto;
            padding: 0 5%;
            display: grid;
            grid-template-columns: 1fr 3fr;
            gap: 2rem;
        }

        .profile-sidebar {
            background-color: var(--bg-secondary);
            border-radius: 20px;
            padding: 2rem;
            text-align: center;
            box-shadow: 0 15px 35px var(--shadow-subtle);
            position: sticky;
            top: 2rem;
            align-self: start;
            transform: perspective(1000px);
            transition: all 0.5s ease;
        }

        .profile-sidebar:hover {
            transform: perspective(1000px) translateZ(50px);
            box-shadow: 0 20px 40px var(--shadow-medium);
        }

        .profile-name {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--text-light);
            margin-bottom: 0.75rem;
        }

        .profile-email {
            color: rgba(255, 255, 255, 0.8);
            font-weight: 300;
            margin-bottom: 1.5rem;
        }

        .profile-actions {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .btn {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            text-align: center;
            position: relative;
            overflow: hidden;
            z-index: 1;
            box-shadow: 0 5px 15px var(--shadow-subtle);
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: rgba(255,255,255,0.2);
            transition: all 0.5s;
            z-index: -1;
        }

        .btn:hover::before {
            left: 0;
        }

        .btn-edit {
            background-color: var(--accent-color);
            color: var(--text-light);
        }

        .btn-logout {
            background-color: #d9534f;
            color: var(--text-light);
        }

        .profile-content {
            display: grid;
            gap: 2rem;
        }

        .content-section {
            background-color: var(--bg-secondary);
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 15px 35px var(--shadow-subtle);
            transform: perspective(1000px);
            transition: all 0.5s ease;
        }

        .content-section:hover {
            transform: perspective(1000px) translateZ(30px);
            box-shadow: 0 20px 40px var(--shadow-medium);
        }

        .section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
            border-bottom: 2px solid var(--accent-color);
            padding-bottom: 0.75rem;
        }

        .section-header h3 {
            color: var(--text-light);
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 1.3rem;
        }

        .section-header i {
            color: var(--accent-color);
        }

        .saved-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 1.5rem;
        }

        .saved-item {
            background-color: var(--text-light);
            border-radius: 15px;
            padding: 1.25rem;
            box-shadow: 0 8px 20px var(--shadow-subtle);
            transition: all 0.4s ease;
            position: relative;
        }

        .saved-item:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 15px 30px var(--shadow-medium);
        }

        .saved-item-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.75rem;
        }

        .saved-item-filename {
            font-weight: 600;
            color: var(--bg-dark);
            max-width: 80%;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .copy-btn {
            background-color: var(--accent-color);
            color: var(--text-light);
            border: none;
            border-radius: 50%;
            width: 35px;
            height: 35px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 4px 10px var(--shadow-subtle);
            transition: all 0.3s ease;
        }

        .copy-btn:hover {
            background-color: #7aad4e;
            transform: scale(1.1) rotate(360deg);
        }

        .delete-btn {
            background-color: #d9534f;
            color: var(--text-light);
            border: none;
            border-radius: 50%;
            width: 35px;
            height: 35px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 4px 10px var(--shadow-subtle);
            transition: all 0.3s ease;
        }

        .delete-btn:hover {
            background-color: #c9302c;
            transform: scale(1.1) rotate(360deg);
        }

        pre {
            background-color: #f4f4f4;
            border-radius: 10px;
            padding: 1rem;
            max-height: 200px;
            overflow-y: auto;
            font-size: 0.85rem;
            white-space: pre-wrap;
            word-wrap: break-word;
            box-shadow: inset 0 3px 10px var(--shadow-subtle);
        }

        .drawer {
            display: none;
            position: fixed;
            top: 0;
            right: 0;
            width: 30%;
            height: 100%;
            background: var(--text-light);
            box-shadow: -2px 0 10px var(--shadow-medium);
            padding: 20px;
            overflow-y: auto;
            transition: transform 0.3s ease-in-out;
            z-index: 1000;
        }

        .drawer.open {
            display: block;
            transform: translateX(0);
        }

        /* Edit Profile Drawer Styles */
        .edit-profile-container {
            padding: 2rem;
        }
        
        .edit-profile-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 2rem;
            border-bottom: 2px solid var(--accent-color);
            padding-bottom: 1rem;
        }
        
        .edit-profile-header h2 {
            color: var(--bg-dark);
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 1.5rem;
            margin: 0;
        }
        
        .edit-profile-header i {
            color: var(--accent-color);
        }
        
        .close-drawer {
            background-color: transparent;
            border: none;
            color: var(--bg-dark);
            font-size: 1.25rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .close-drawer:hover {
            color: #d9534f;
            transform: scale(1.1);
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: var(--bg-dark);
        }
        
        .form-input {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid #d1d1d1;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        
        .form-input:focus {
            border-color: var(--accent-color);
            outline: none;
            box-shadow: 0 0 0 3px rgba(140, 191, 90, 0.2);
        }
        
        .submit-btn {
            background-color: var(--accent-color);
            color: white;
            border: none;
            border-radius: 8px;
            padding: 0.75rem 1.5rem;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 100%;
            margin-top: 1rem;
        }
        
        .submit-btn:hover {
            background-color: #7aad4e;
            transform: translateY(-2px);
            box-shadow: 0 6px 8px rgba(0, 0, 0, 0.15);
        }
        
        .submit-btn:active {
            transform: translateY(1px);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        .password-wrapper {
            position: relative;
        }
        
        .toggle-password {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            background: transparent;
            border: none;
            color: #777;
            cursor: pointer;
        }
        
        .toggle-password:hover {
            color: var(--accent-color);
        }

        @media (max-width: 1024px) {
            .profile-container {
                grid-template-columns: 1fr;
            }

            .profile-sidebar {
                position: static;
            }
            
            .drawer {
                width: 50%;
            }
        }

        @media (max-width: 768px) {
            .nav-links {
                display: none;
            }
            
            .drawer {
                width: 80%;
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
                <li><a href="cheat.php">Cheat Sheet</a></li>
                <li><a href="profile.php">Profile</a></li>
            </ul>
        </div>
    </nav>
    
    <div class="profile-container">
        <div class="profile-sidebar">
            <h2 class="profile-name"><?php echo htmlspecialchars($user['username']); ?></h2>
            <p class="profile-email"><?php echo htmlspecialchars($user['email']); ?></p>
            <div class="profile-actions">
                <button onclick="openEditProfile()" class="btn btn-edit"><i class="fas fa-edit"></i> Edit Profile</button>
                <a href="logout.php" class="btn btn-logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </div>

        <div class="profile-content">
            <div class="content-section">
                <div class="section-header">
                    <h3><i class="fas fa-code"></i> Saved Exercises </h3>
                </div>
                <div class="saved-grid">
                    <?php 
                    if ($code_results->num_rows > 0) {
                        while ($code = $code_results->fetch_assoc()) { ?>
                            <div class="saved-item">
                                <div class="saved-item-header">
                                    <span class="saved-item-filename">
                                        <?php echo htmlspecialchars($code['filename']); ?>
                                    </span>
                                    <button class="copy-btn" onclick="copyCode('code-<?php echo $code['id']; ?>')">
                                        <i class="fas fa-copy"></i>
                                    </button>
                                    <button class="delete-btn" onclick="deleteItem('code', <?php echo $code['id']; ?>)">
                                    <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                                <pre id="code-<?php echo $code['id']; ?>"><?php echo htmlspecialchars($code['code']); ?></pre>
                            </div>
                        <?php } 
                    } else {
                        echo '<p style="color: var(--bg-dark); grid-column: 1 / -1; text-align: center;">No saved exercises yet. Keep practicing!</p>';
                    }
                    ?>
                </div>
            </div>

            <div class="content-section">
                <div class="section-header">
                    <h3><i class="fas fa-file-alt"></i> Saved Codes </h3>
                </div>
                <div class="saved-grid">
                    <?php 
                    if ($exercise_results->num_rows > 0) {
                        while ($exercise = $exercise_results->fetch_assoc()) { ?>
                            <div class="saved-item">
                                <div class="saved-item-header">
                                    <span class="saved-item-filename">
                                        <?php echo htmlspecialchars($exercise['filename']); ?>
                                    </span>
                                    <button class="copy-btn" onclick="copyCode('exercise-<?php echo $exercise['id']; ?>')">
                                        <i class="fas fa-copy"></i>
                                    </button>
                                    <button class="delete-btn" onclick="deleteItem('exercise', <?php echo $exercise['id']; ?>)">
                                    <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                                <pre id="exercise-<?php echo $exercise['id']; ?>"><?php echo htmlspecialchars($exercise['code']); ?></pre>
                            </div>
                        <?php } 
                    } else {
                        echo '<p style="color: var(--bg-dark); grid-column: 1 / -1; text-align: center;">No saved code yet. Start coding!</p>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Redesigned Edit Profile Drawer -->
    <div id="editProfileDrawer" class="drawer" style="display: none;">
        <div id="editProfileContent"></div>
    </div>
    
    <script>
        function copyCode(elementId) {
            var codeElement = document.getElementById(elementId);
            var textArea = document.createElement("textarea");
            textArea.value = codeElement.innerText;
            document.body.appendChild(textArea);
            textArea.select();
            document.execCommand("copy");
            document.body.removeChild(textArea);
            alert("Code copied to clipboard!");
        }

        function deleteItem(type, id) {
            if (confirm("Are you sure you want to delete this item?")) {
                // Fix the URL mapping
                let url = type === 'exercise' ? 'delete_exercise.php' : 'delete_code.php';
                
                fetch(url, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: 'id=' + id
                })
                .then(response => response.text())
                .then(data => {
                    try {
                        // Try to parse as JSON first
                        const jsonData = JSON.parse(data);
                        alert(jsonData.message);
                    } catch (e) {
                        // If not JSON, treat as plain text
                        alert(data);
                    }
                    location.reload();
                })
                .catch(error => console.error('Error:', error));
            }
        }

        function openEditProfile() {
            document.getElementById("editProfileDrawer").style.display = "block";
            fetch("edit_profile.php")
                .then(response => response.text())
                .then(data => {
                    document.getElementById("editProfileContent").innerHTML = data;
                });
        }

        function closeEditProfile() {
            document.getElementById("editProfileDrawer").style.display = "none";
        }
        
        // For password toggle in the edit profile form
        document.addEventListener('click', function(e) {
            if (e.target && e.target.classList.contains('toggle-password') || 
                (e.target.parentElement && e.target.parentElement.classList.contains('toggle-password'))) {
                const passwordInput = document.getElementById('password');
                if (passwordInput) {
                    const toggleIcon = document.querySelector('.toggle-password i');
                    if (passwordInput.type === 'password') {
                        passwordInput.type = 'text';
                        toggleIcon.classList.remove('fa-eye');
                        toggleIcon.classList.add('fa-eye-slash');
                    } else {
                        passwordInput.type = 'password';
                        toggleIcon.classList.remove('fa-eye-slash');
                        toggleIcon.classList.add('fa-eye');
                    }
                }
            }
        });
    </script>
    
</body>
</html>