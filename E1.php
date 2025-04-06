<?php
session_start();
include 'db_connection.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Make user_id available to JavaScript
$user_id = $_SESSION['user_id'];
?>

<!DOCTYPE htm>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Java Basics - Exercise 1</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.4.14/ace.js"></script>
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
        body {
            font-family: Arial, sans-serif;
            background-color: #e9e5d6;
            margin: 0;
            padding: 0;
            color: #333;
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

        .nav-links a::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background-color: var(--color-accent);
            transition: var(--transition);
        }

        .nav-links a:hover::after {
            width: 100%;
        }
        
        h2, h3 {
            color: #2f2f2f;
        }
        
        .page-title {
            text-align: center;
            font-size: 32px;
            margin: 40px 0;
        }
        
        .exercise-container {
            width: 90%;
            max-width: 1100px;
            margin: 20px auto 50px;
            background: #fff;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        
        .instructions {
            background: #a4b66c;
            padding: 20px;
            border-radius: 10px;
            font-size: 16px;
            margin-bottom: 30px;
            color: #2f2f2f;
        }
        
        .exercise-content {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        
        .code-section {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }
        
        .panel-header {
            background: #a4b66c;
            color: #2f2f2f;
            padding: 10px 15px;
            border-radius: 10px 10px 0 0;
            font-weight: bold;
        }
        
        #editor {
            height: 250px;
            font-size: 16px;
            border-radius: 0 0 10px 10px;
        }
        
        .preview-box {
            background: #f5f5f5;
            padding: 20px;
            border-radius: 10px;
            min-height: 100px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }
        
        #preview {
            font-family: monospace;
            white-space: pre;
            font-size: 20px;
            line-height: 1.5;
            color: #333;
            font-weight: 500;
        }
        
        .editor-buttons {
            display: flex;
            gap: 15px;
            margin: 20px 0;
        }
        
        .editor-buttons button {
            background: #a4b66c;
            color: #2f2f2f;
            border: none;
            padding: 12px 25px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            transition: background 0.3s;
        }
        
        .editor-buttons button:hover {
            background: #b9ca86;
        }
        
        #answer-box {
            background: #f5f5f5;
            padding: 15px;
            border-left: 4px solid #a4b66c;
            display: none;
            font-family: monospace;
            font-size: 18px;
            white-space: pre;
            border-radius: 5px;
            margin-top: 20px;
        }
        
        .footer {
            background-color: #2f2f2f;
            color: white;
            text-align: center;
            padding: 15px 0;
            position: relative;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>
<body>

    <!-- Navigation Section --> 
    <nav class="navbar">
        <div class="nav-container">
            <a href="index.php" class="logo">Codelab</a>
            <ul class="nav-links">
                <li><a href="editor.php">Code Editor</a></li>
                <li><a href="exercise.html">Exercises</a></li>
                <li><a href="cheat.php">Cheet Sheet</a></li>
                <li><a href="profile.php">Profile</a></li>
            </ul>
        </div>
    </nav>

    <div class="exercise-container">
        <h2>Java Basics: Hello World</h2>
        
        <!-- Instructions Section -->
        <div class="instructions">
            <h3>Instructions</h3>
            <p>Complete the following task:</p>
            <ol>
                <li>Write a Java program that prints <code>Hello, World!</code> to the console.</li>
                <li>Use the <code>System.out.println()</code> method inside the <code>main</code> method.</li>
                <li>Click the "Run" button to check your output.</li>
            </ol>
        </div>

        <!-- Code Editor Section -->
        <div class="exercise-content">
            <div class="panel-header">Code</div>
            <div id="editor" placeholder="Write your Java code here..."></div>
            
            <div class="editor-buttons">
                <button id="runCode">Run</button>
                <button id="saveCode">Save</button>
                <button id="showAnswer">Show Answer</button>
            </div>
            <pre id="answer-box"></pre>
        </div>

        <!-- Live Preview Section -->
        <div class="preview-box">
            <div class="panel-header">Preview</div>
            <div id="preview">Output will appear here...</div>
        </div>
    </div>

    <footer class="footer">
        <p>&copy; 2025 Preprog. All Rights Reserved.</p>
    </footer>

    <script>
    document.addEventListener("DOMContentLoaded", function () {
        // Verify user_id is a non-empty value
        let user_id = <?php echo json_encode(isset($_SESSION['user_id']) ? $_SESSION['user_id'] : ''); ?>;
        
        // Check if user_id is truthy (not empty, not null, not undefined)
        if (!user_id) {
            alert("You are not logged in! Redirecting to login page.");
            window.location.href = "login.php";
            return;
        }
    });
    
        // Initialize Ace Editor
        let editor = ace.edit("editor"); 
        editor.setTheme("ace/theme/monokai");
        editor.session.setMode("ace/mode/java");
        editor.setFontSize("16px");
        editor.setShowPrintMargin(false);
        editor.setOptions({
            enableBasicAutocompletion: true,
            enableLiveAutocompletion: true,
            enableSnippets: true
        });

        
    // Set filename dynamically for this exercise
    var filename = "e1";

        // Run Code using Judge0 API
        document.getElementById("runCode").addEventListener("click", function () {
            let code = editor.getValue();
            let languageId = 62; // Java (62)

            let requestData = {
                source_code: btoa(code), // Encode code to base64
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
                document.getElementById("preview").textContent = atob(data.stdout || "No Output");
            })
            .catch(error => console.error("Error:", error));
        });

        // Save Code to Database
        document.getElementById("saveCode").addEventListener("click", function () {
            let filename = "Exercise 1";
            let code = editor.getValue();
            let user_id = <?php echo json_encode($_SESSION['user_id']); ?>;

            if (!user_id) {
                alert("User is not logged in!");
                return;
            }

            if (code) {
                fetch("save_code.php", { 
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
                alert("Code cannot be empty!");
            }
        });

        // Show Answer Function
        document.getElementById("showAnswer").addEventListener("click", function() {
            let answerBox = document.getElementById("answer-box");
            
            // Toggle answer
            if (answerBox.style.display === "block") {
                answerBox.style.display = "none";
            } else {
                answerBox.innerText = `public class Main {
    public static void main(String[] args) {
        System.out.println("Hello, World!");
    }
}`;
                answerBox.style.display = "block";
            }
        });

        // Load saved code on page refresh
        document.addEventListener("DOMContentLoaded", function() {
            let savedCode = localStorage.getItem("savedCode");
            if (savedCode) {
                editor.setValue(savedCode);
            }
        });
    </script>
</body>
</html>