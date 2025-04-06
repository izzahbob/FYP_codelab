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
        
        //save code to db
        document.getElementById("saveCode").addEventListener("click", function () {
            let filename = prompt("Enter filename to save:");
            let code = editor.getValue();
            let user_id = <?php echo json_encode($_SESSION['user_id']); ?>;

            if (!user_id) {
                alert("User is not logged in!");
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
                    alert(data.message || "Code saved successfully!");
                })
                .catch(error => {
                    console.error("Error:", error);
                    alert("Error saving code!");
                });
            } else {
                alert("Filename and code cannot be empty!");
            }
        });

        //run code using Judge0 API
        document.getElementById("runCode").addEventListener("click", function () {
            let code = editor.getValue(); //code drpd editor
            let languageId = 62; // Java (62); jugde0 guna java 62

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
            })
            .catch(error => console.error("Error:", error));
        });
        
        //reset button
        document.getElementById("resetEditor").addEventListener("click", function () {
            editor.setValue(""); // Clears the Ace editor content
            document.getElementById("preview").textContent = ""; // Optional: clear preview output
        });

    </script>
    
</body>
</html>
