<?php
session_start();
include 'db_connection.php';

// This is correct - keep this part
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// You might want to add this to make the user_id available to JavaScript
$user_id = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Codelab - Editor</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.4.14/ace.js"></script>
    <style>
        :root {
            --color-bg: #e2dfcf;
            --color-dark: #36332d;
            --color-medium: #6d685f;
            --color-accent: #a3b45c;
            --color-content: #d6d2bd;
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
            margin: 0;
            padding: 0;
        }

        .container {
            width: 95%;
            max-width: 1200px;
            margin: 30px auto;
            padding: 0;
        }

        .navbar {
            background: var(--color-dark);
            padding: 15px 0;
            box-shadow: var(--shadow);
            position: sticky;
            top: 0;
            z-index: 100;
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

        .editor-wrapper {
            background: var(--color-content);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            overflow: hidden;
            padding: 25px;
            margin-bottom: 30px;
        }

        .editor-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .editor-container {
            display: flex;
            gap: 20px;
            height: 500px;
            margin-bottom: 25px;
        }

        .code-panel {
            flex: 1;
            display: flex;
            flex-direction: column;
            background: var(--color-dark);
            border-radius: var(--radius);
            overflow: hidden;
        }

        .panel-header {
            background: var(--color-medium);
            color: white;
            padding: 10px 15px;
            font-weight: 500;
        }

        #editor {
            flex: 1;
            width: 100%;
            border: none;
            padding: 15px;
            font-size: 16px;
            font-family: monospace;
            background: var(--color-dark);
            color: white;
            resize: none;
            outline: none;
        }

        .preview-panel {
            flex: 1;
            display: flex;
            flex-direction: column;
            background: white;
            border-radius: var(--radius);
            overflow: hidden;
        }

        .btn-group {
            display: flex;
            gap: 10px;
        }

        .btn {
            background: var(--color-dark);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: var(--radius);
            cursor: pointer;
            font-size: 15px;
            font-weight: 500;
            transition: var(--transition);
        }

        .btn:hover {
            background: var(--color-medium);
            transform: translateY(-2px);
        }
        
        /* Added Modal Styles - matching cheat.php */
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

        .form-group {
            margin-bottom: 20px;
        }

        input[type="text"], textarea {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: var(--radius);
            background-color: #f8f8f8;
            color: var(--color-dark);
            font-family: var(--font-main);
            font-size: 16px;
            transition: var(--transition);
        }

        input[type="text"]:focus, textarea:focus {
            outline: none;
            border-color: var(--color-accent);
            box-shadow: 0 0 0 2px rgba(163, 180, 92, 0.2);
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
    </style>
</head>
<body>
    <!-- Navigation Section --> 
    <nav class="navbar">
        <div class="nav-container">
            <a href="homepage.php" class="logo">Codelab</a>
            <ul class="nav-links">
                <li><a href="homepage.php">Home</a></li>
                <li><a href="exercise.html">Exercises</a></li>
                <li><a href="cheat.php">Cheet Sheet</a></li>
                <li><a href="profile.php">Profile</a></li>
            </ul>
        </div>
    </nav>

    <div class="container">
        <!-- buttons section -->
        <div class="editor-wrapper">
            <div class="editor-header">
                <h2>Interactive Code Editor</h2>
                <div class="btn-group">
                    <button id="resetEditor" class="btn">Reset</button>
                    <button id="runCode" class="btn">Run</button>
                    <button id="saveCode" class="btn">Save</button>
                </div>
            </div>

            <!-- code editor section -->
            <div class="editor-container">
                <div class="code-panel">
                    <div class="panel-header">Code</div>
                    <div id="editor" placeholder="Write your Java code here..."></div>
                </div>

                <!-- output section -->
                <div class="preview-panel">
                    <div class="panel-header">Preview</div>
                    <div id="preview"></div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Save Modal -->
    <div id="save-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2><i class="fas fa-save"></i> Save Code</h2>
                <button type="button" class="close-button" onclick="closeSaveModal()">&times;</button>
            </div>
            <div class="modal-body">
                <form id="save-code-form">
                    <div class="form-group">
                        <input type="text" id="filename" name="filename" placeholder="Enter filename" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeSaveModal()">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="saveCodeToDb()">Save</button>
            </div>
        </div>
    </div>
    
    <!-- Confirm Reset Modal -->
    <div id="reset-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2><i class="fas fa-exclamation-triangle"></i> Confirm Reset</h2>
                <button type="button" class="close-button" onclick="closeResetModal()">&times;</button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to reset the editor? All unsaved code will be lost.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeResetModal()">Cancel</button>
                <button type="button" class="btn btn-danger" onclick="resetEditorContent()">Reset</button>
            </div>
        </div>
    </div>

    <!-- Toast notification -->
    <div id="toast" class="toast">
        <i class="fas fa-check-circle"></i>
        <span id="toast-message"></span>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
        let user_id = <?php echo json_encode($_SESSION['user_id']); ?>;
        if (!user_id) {
            alert("You are not logged in! Redirecting to login page.");
            window.location.href = "login.php";
        }
    });
    
        //apply ace editor
        let editor = ace.edit("editor"); //attach ace to div 
        editor.setTheme("ace/theme/monokai"); //dark theme hehe
        editor.session.setMode("ace/mode/java"); //mode language: java
        editor.setFontSize("16px"); //font size
        editor.setShowPrintMargin(false); //hide print margin
        editor.setOptions({
            enableBasicAutocompletion: true, // Auto-completion
            enableLiveAutocompletion: true,  // Live auto-completion
            enableSnippets: true             // Code snippets
        })
        
        // Updated save code functionality to use modal
        document.getElementById("saveCode").addEventListener("click", function () {
            openSaveModal();
        });
        
        function openSaveModal() {
            document.getElementById('save-modal').style.display = 'flex';
            document.getElementById('filename').focus();
        }
        
        function closeSaveModal() {
            document.getElementById('save-modal').style.display = 'none';
        }
        
        function saveCodeToDb() {
            let filename = document.getElementById('filename').value;
            let code = editor.getValue();
            let user_id = <?php echo json_encode($_SESSION['user_id']); ?>;

            if (!user_id) {
                showToast("User is not logged in!", "error");
                return;
            }

            if (filename && code) {
                fetch("save_code2.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({ user_id: user_id, filename: filename, code: code })
                })
                .then(response => response.json())
                .then(data => {
                    showToast(data.message || "Code saved successfully!", "success");
                    closeSaveModal();
                    document.getElementById('save-code-form').reset();
                })
                .catch(error => {
                    console.error("Error:", error);
                    showToast("Error saving code!", "error");
                });
            } else {
                showToast("Filename and code cannot be empty!", "error");
            }
        }

        // Updated run code functionality
        document.getElementById("runCode").addEventListener("click", function () {
            let code = editor.getValue(); //code drpd editor
            let languageId = 62; // Java (62); jugde0 guna java 62
            
            // Show toast that code is running
            showToast("Running code...", "success");

            let requestData = {
                source_code: btoa(code), //convert code ke base62
                language_id: languageId,
                stdin: "" 
            }; 

            fetch("https://judge0-ce.p.rapidapi.com/submissions?base64_encoded=true&wait=true", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-RapidAPI-Host": "judge0-ce.p.rapidapi.com",
                    "X-RapidAPI-Key": "0f7ddac18cmsh613e77fdbee5b34p117992jsnb34e7c602da8"
                },
                body: JSON.stringify(requestData)
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById("preview").textContent = atob(data.stdout || "No Output"); //display output kt preview
                showToast("Code executed successfully!", "success");
            })
            .catch(error => {
                console.error("Error:", error);
                showToast("Error running code!", "error");
            });
        });
        
        // Updated reset button functionality to use confirm modal
        document.getElementById("resetEditor").addEventListener("click", function () {
            openResetModal();
        });
        
        function openResetModal() {
            document.getElementById('reset-modal').style.display = 'flex';
        }
        
        function closeResetModal() {
            document.getElementById('reset-modal').style.display = 'none';
        }
        
        function resetEditorContent() {
            editor.setValue(""); // Clears the Ace editor content
            document.getElementById("preview").textContent = ""; // Clear preview output
            closeResetModal();
            showToast("Editor reset successfully!", "success");
        }
        
        // Show Toast notification
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
            const saveModal = document.getElementById('save-modal');
            const resetModal = document.getElementById('reset-modal');
            
            if (event.target === saveModal) {
                closeSaveModal();
            }
            
            if (event.target === resetModal) {
                closeResetModal();
            }
        }
        
        // Handle form submission in the save modal
        document.getElementById('save-code-form').addEventListener('submit', function(e) {
            e.preventDefault();
            saveCodeToDb();
        });
    </script>
    
</body>
</html>