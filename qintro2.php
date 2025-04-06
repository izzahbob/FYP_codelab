<?php
session_start([
    'cookie_httponly' => true,
    'cookie_secure' => true,
    'cookie_samesite' => 'Lax'
]);

if (!isset($_SESSION['quiz_attempts'])) {
    $_SESSION['quiz_attempts'] = 0;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beginner's Quiz - JavaScript</title>
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
            counter-reset: section;
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

        /* Main container */
        .container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 20px;
        }

        /* Header Section */
        .header-section {
            background-color: var(--color-accent);
            color: var(--color-dark);
            padding: 40px 30px;
            border-radius: var(--radius);
            margin-bottom: 40px;
            text-align: center;
            box-shadow: var(--shadow);
        }

        .header-section h1 {
            font-size: 32px;
            margin-bottom: 15px;
        }

        .header-section p {
            font-size: 18px;
            max-width: 800px;
            margin: 0 auto 15px;
        }

        /* Info Cards - Vertical Layout */
        .info-cards-container {
            display: flex;
            flex-direction: column;
            gap: 25px;
            margin-bottom: 50px;
            max-width: 900px;
            margin-left: auto;
            margin-right: auto;
        }

        .info-card {
            background-color: white;
            border-radius: var(--radius);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            transition: var(--transition);
            border-left: 4px solid var(--color-accent);
        }

        .info-card:hover {
            box-shadow: 0 6px 14px rgba(0, 0, 0, 0.1);
            transform: translateX(5px);
        }

        .card-header {
            padding: 16px 20px;
            color: var(--color-dark);
            font-size: 20px;
            font-weight: bold;
            background-color: #f9f9f9;
            border-bottom: 1px solid #eee;
        }

        .card-header h3 {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .card-header h3::before {
            content: counter(section) ".";
            counter-increment: section;
            font-weight: bold;
            color: var(--color-accent);
        }

        .card-content {
            padding: 20px;
        }

        .card-content p {
            margin-bottom: 15px;
            color: var(--color-dark);
            line-height: 1.7;
        }

        .card-content ul {
            margin-left: 5px;
            margin-bottom: 20px;
            list-style-type: none;
        }

        .card-content li {
            margin-bottom: 12px;
            position: relative;
            padding-left: 24px;
            line-height: 1.5;
        }

        .card-content li:before {
            content: '•';
            color: var(--color-accent);
            font-weight: bold;
            position: absolute;
            left: 0;
            font-size: 18px;
        }

        .card-content strong {
            color: var(--color-dark);
            font-weight: 600;
        }

        .code-example {
            background-color: #f8f8f8;
            padding: 16px;
            border-radius: 6px;
            font-family: 'Courier New', monospace;
            overflow-x: auto;
            font-size: 14px;
            line-height: 1.6;
            border-left: 3px solid var(--color-accent);
            margin-top: 10px;
        }

        /* Quiz Controls */
        .btn-primary {
            padding: 15px 40px;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            background-color: var(--color-dark);
            color: var(--color-bg);
            border: none;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            transition: var(--transition);
            position: relative;
            overflow: hidden;
            z-index: 1;
            margin: 10px auto;
            display: inline-block;
        }

        .btn-primary:hover {
            background-color: var(--color-medium);
            color: white;
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }

        .btn-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: 0.5s;
            z-index: -1;
        }

        .btn-primary:hover::before {
            left: 100%;
        }

        .attempts-info {
            margin-top: 20px;
            font-size: 16px;
            color: var(--color-medium);
        }

        footer {
            text-align: center;
            margin-top: 50px;
            color: var(--color-medium);
            padding: 20px;
            background-color: var(--color-dark);
            color: white;
        }

        /* Add keyframe animations */
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        /* Add pulsing animation to main button */
        .btn-primary {
            animation: pulse 2s infinite;
        }

        .btn-primary:hover {
            animation: none;
        }

        @media (max-width: 768px) {
            .card-header {
                padding: 14px 16px;
            }
            
            .card-content {
                padding: 16px;
            }
        }

        @media (max-width: 576px) {
            .nav-container {
                flex-direction: column;
            }
            
            .nav-links {
                margin-top: 15px;
            }
            
            .nav-links li {
                margin-left: 10px;
                margin-right: 10px;
            }

            .header-section {
                padding: 25px 15px;
            }
            
            .header-section h1 {
                font-size: 26px;
            }

            .btn-primary {
                padding: 12px 25px;
                font-size: 16px;
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
                <li><a href="cheat.php">Cheet Sheet</a></li>
                <li><a href="profile3.php">Profile</a></li>
            </ul>
        </div>
    </nav>
    
    <div class="container">
        <!-- Header Section -->
        <div class="header-section">
            <h1>Welcome to Intermediate's JavaScript Quiz!</h1>
            <p>This quiz tests your knowledge on JavaScript fundamentals. Review the key concepts below before starting.</p>
            
                <button id="start-quiz-btn" class="btn-primary" onclick="startQuiz()">
                    <i class="fas fa-play-circle"></i> Start Quiz
                </button>
                <?php if ($_SESSION['quiz_attempts'] > 0): ?>
                <p class="attempts-info">You've taken this quiz <?php echo $_SESSION['quiz_attempts']; ?> time(s) before.</p>
                <?php endif; ?>
        
        </div>

        <!-- Info Cards Vertical Layout -->
        <div class="info-cards-container">
            <div class="info-card">
                <div class="card-header"><h3>Array.prototype.map()</h3></div>
                <div class="card-content">
                    <p>The <code>map()</code> method creates a new array by applying a function to every element in the original array. It does not change the original array. For example, <code>[1, 2, 3].map(x => x * 2)</code> returns <code>[2, 4, 6]</code>.</p>
                </div>
            </div>
            
            <div class="info-card">
                <div class="card-header"><h3>Exception Handling</h3></div>
                <div class="card-content">
                    <p>JavaScript uses <code>try</code>, <code>catch</code>, and <code>throw</code> to handle errors. Code that might fail goes in <code>try</code>, and the <code>catch</code> block handles what to do if there's an error. You can use <code>throw</code> to trigger your own custom errors.</p>
                </div>
            </div>
            
            <div class="info-card">
                <div class="card-header"><h3>typeof Operator</h3></div>
                <div class="card-content">
                    <p>The <code>typeof</code> operator is used to check the type of a variable, such as <code>string</code>, <code>number</code>, <code>boolean</code>, etc. It returns a string describing the type of the operand, like <code>typeof "text"</code> → <code>"string"</code>.</p>
                </div>
            </div>
            
            <div class="info-card">
                <div class="card-header"><h3>Type Conversion</h3></div>
                <div class="card-content">
                    <p>JavaScript provides <code>parseInt()</code>, <code>Number()</code>, and <code>parseFloat()</code> to convert strings into numbers. These methods handle different formats, with <code>parseInt()</code> best for whole numbers and <code>parseFloat()</code> for decimals.</p>
                </div>
            </div>
            
            <div class="info-card">
                <div class="card-header"><h3>splice() Method</h3></div>
                <div class="card-content">
                    <p>The <code>splice()</code> method modifies an array by removing, replacing, or adding elements. Example: <code>arr.splice(1, 2)</code> removes two elements starting from index 1.</p>
                </div>
            </div>
            
            <div class="info-card">
                <div class="card-header"><h3>do...while Loop</h3></div>
                <div class="card-content">
                    <p>The <code>do...while</code> loop always runs at least once before checking its condition. This is useful when the loop should run first and validate later.</p>
                </div>
            </div>
            
            <div class="info-card">
                <div class="card-header"><h3>Function Scope</h3></div>
                <div class="card-content">
                    <p>Variables declared inside a function are not accessible outside—it creates its own function scope. This keeps code modular and protects variables from outside changes.</p>
                </div>
            </div>
            
            <div class="info-card">
                <div class="card-header"><h3>Loose Equality: <code>false == 0</code></h3></div>
                <div class="card-content">
                    <p>Using <code>==</code> in JavaScript performs type coercion. So <code>false == 0</code> is <code>true</code> because false is coerced into a number. Use <code>===</code> to avoid this behavior.</p>
                </div>
            </div>
            
            <div class="info-card">
                <div class="card-header"><h3>Strict Equality: <code>===</code></h3></div>
                <div class="card-content">
                    <p>The <code>===</code> operator checks if both value and type are equal. For example, <code>"5" === 5</code> is false because one is a string and the other is a number.</p>
                </div>
            </div>
            
            <div class="info-card">
                <div class="card-header"><h3>Callback Functions</h3></div>
                <div class="card-content">
                    <p>A callback is a function passed into another function to run later. It’s commonly used in asynchronous operations like loading data. Example: <code>setTimeout(() => { ... }, 1000)</code>.</p>
                </div>
            </div>
            
            <div class="info-card">
                <div class="card-header"><h3>return Keyword</h3></div>
                <div class="card-content">
                    <p>The <code>return</code> keyword stops a function and sends back a value. You can return numbers, strings, or even other functions.</p>
                </div>
            </div>
            
            <div class="info-card">
                <div class="card-header"><h3>push() Method</h3></div>
                <div class="card-content">
                    <p><code>push()</code> adds new items to the end of an array. It returns the new length of the array. Example: <code>arr.push(4)</code> adds 4 to the array.</p>
                </div>
            </div>
            
            <div class="info-card">
                <div class="card-header"><h3>typeof null</h3></div>
                <div class="card-content">
                    <p>Strangely, <code>typeof null</code> returns <code>"object"</code>. This is a long-standing bug in JavaScript that has never been fixed for compatibility reasons.</p>
                </div>
            </div>
            
            <div class="info-card">
                <div class="card-header"><h3>concat() Method</h3></div>
                <div class="card-content">
                    <p><code>concat()</code> is used to combine two or more arrays into one. Example: <code>[1,2].concat([3,4])</code> → <code>[1,2,3,4]</code>. It doesn’t change the original arrays.</p>
                </div>
            </div>
            
            <div class="info-card">
                <div class="card-header"><h3>Type Coercion in Addition</h3></div>
                <div class="card-content">
                    <p>When adding a string and a number, JavaScript converts the number to a string. So <code>"10" + 5</code> becomes <code>"105"</code>.</p>
                </div>
            </div>
            
            <div class="info-card">
                <div class="card-header"><h3>includes() Method</h3></div>
                <div class="card-content">
                    <p>The <code>includes()</code> method checks if an array contains a certain value. Example: <code>[1, 2, 3].includes(2)</code> returns <code>true</code>.</p>
                </div>
            </div>
            
            <div class="info-card">
                <div class="card-header"><h3>Checking if a Variable is an Array</h3></div>
                <div class="card-content">
                    <p>Use <code>Array.isArray(variable)</code> to check if a value is an array. Other checks like <code>typeof</code> won't work because arrays are technically objects.</p>
                </div>
            </div>
            
            <div class="info-card">
                <div class="card-header"><h3>Accessing Elements by ID</h3></div>
                <div class="card-content">
                    <p>Use <code>document.getElementById("id")</code> to get an HTML element by its ID. It's a fundamental way to manipulate DOM content.</p>
                </div>
            </div>
            
            <div class="info-card">
                <div class="card-header"><h3>event.preventDefault()</h3></div>
                <div class="card-content">
                    <p><code>preventDefault()</code> is used in event handlers to stop the default behavior of the browser. For example, stopping a form from submitting when clicking a button.</p>
                </div>
            </div>
            
            <div class="info-card">
                <div class="card-header"><h3>trim() Method</h3></div>
                <div class="card-content">
                    <p>The <code>trim()</code> method removes whitespace from both the beginning and the end of a string. It’s useful when cleaning up user input or form data.</p>
                </div>
            </div>

        </div>
    </div>

    <footer>
        <p>&copy; <?php echo date('Y'); ?> Codelab - JavaScript Quiz</p>
    </footer>

    <script>
        function startQuiz() {
            window.location.href = 'quiz2.php';
        }
    </script>
</body>
</html>