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
            <h1>Welcome to Beginner's JavaScript Quiz!</h1>
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
                <div class="card-header"><h3>Variable Declarations</h3></div>
                <div class="card-content">
                    <p>In JavaScript, you can declare variables using <code>let</code>, <code>const</code>, or <code>var</code>. However, modern JavaScript prefers <code>let</code> for variables that change and <code>const</code> for values that stay the same. These declarations are block-scoped, meaning they only exist within the block where they're defined. Avoid using <code>var</code> unless you're working with older code, as it has more confusing scoping behavior.</p>
                </div>
            </div>

            <div class="info-card">
                <div class="card-header"><h3>JavaScript Data Types</h3></div>
                <div class="card-content">
                    <p>JavaScript supports several basic data types, including <code>String</code>, <code>Number</code>, <code>Boolean</code>, <code>Undefined</code>, and <code>Null</code>. The most common are <code>String</code> (text in quotes), <code>Number</code> (for all numbers), and <code>Boolean</code> (true/false). JavaScript is dynamically typed, meaning variables can hold any type of value at any time.</p>
                </div>
            </div>

            <div class="info-card">
                <div class="card-header"><h3>Comments in JavaScript</h3></div>
                <div class="card-content">
                    <p>Comments help explain your code without affecting how it runs. Use <code>//</code> for single-line comments and <code>/* ... */</code> for multi-line comments. Comments are great for adding notes or explaining tricky parts of your code to yourself or others.</p>
                </div>
            </div>

            <div class="info-card">
                <div class="card-header"><h3>Type Coercion</h3></div>
                <div class="card-content">
                    <p>JavaScript is known for type coercion, where it automatically converts values from one type to another. For example, <code>2 + "2"</code> becomes <code>"22"</code> because the number <code>2</code> is converted into a string before addition. This can sometimes lead to confusing results, so it's important to understand how JavaScript handles types behind the scenes.</p>
                </div>
            </div>

            <div class="info-card">
                <div class="card-header"><h3>Assignment vs Comparison</h3></div>
                <div class="card-content">
                    <p>In JavaScript, <code>=</code> is used for assignment (e.g., <code>x = 5</code>), while <code>==</code> checks if values are equal, and <code>===</code> checks if values and types are equal. It's a common mistake to confuse <code>=</code> with <code>==</code>, so always double-check when writing conditions.</p>
                </div>
            </div>

            <div class="info-card">
                <div class="card-header"><h3>Arrays and Objects</h3></div>
                <div class="card-content">
                    <p>Arrays hold ordered lists of items, like <code>[1, 2, 3]</code>, while objects hold key-value pairs, like <code>{name: "Alex", age: 20}</code>. Arrays are great for lists, and objects are best when you need to store related information with labels (keys).</p>
                </div>
            </div>

            <div class="info-card">
                <div class="card-header"><h3>Using the Console</h3></div>
                <div class="card-content">
                    <p>The <code>console.log()</code> function allows you to print messages to the browser's developer console. This is incredibly useful for debugging your code and checking if your variables and functions are working correctly.</p>
                </div>
            </div>

            <div class="info-card">
                <div class="card-header"><h3>typeof Operator</h3></div>
                <div class="card-content">
                    <p>The <code>typeof</code> operator lets you find out the data type of a value. For example, <code>typeof "hello"</code> returns <code>"string"</code>. This is especially helpful when you're not sure what kind of value a variable is storing.</p>
                </div>
            </div>

            <div class="info-card">
                <div class="card-header"><h3>Functions and return</h3></div>
                <div class="card-content">
                    <p>Functions are blocks of reusable code. You can pass values into them and get a result using the <code>return</code> keyword. <code>return</code> stops the function and sends back the value you want to use elsewhere in your program.</p>
                </div>
            </div>

            <div class="info-card">
                <div class="card-header"><h3>Array Methods</h3></div>
                <div class="card-content">
                    <p>JavaScript arrays come with many helpful methods. <code>join()</code> combines all elements into one string, <code>push()</code> adds to the end, and <code>pop()</code> removes from the end. These make it easy to manage collections of data.</p>
                </div>
            </div>

            <div class="info-card">
                <div class="card-header"><h3>Logical Operators</h3></div>
                <div class="card-content">
                    <p>Logical operators like <code>&&</code> (AND) and <code>||</code> (OR) are used in conditions. For example, <code>true && false</code> returns <code>false</code> because both sides must be true for the result to be true. These are essential for building decision-making logic in your code.</p>
                </div>
            </div>

            <div class="info-card">
                <div class="card-header"><h3>Semicolons</h3></div>
                <div class="card-content">
                    <p>JavaScript doesn't require semicolons at the end of every statement, but it's considered best practice to use them. They help avoid unexpected errors in more complex code and keep your syntax clean and predictable.</p>
                </div>
            </div>

            <div class="info-card">
                <div class="card-header"><h3>Object Declaration</h3></div>
                <div class="card-content">
                    <p>Objects in JavaScript are created using curly braces <code>{}</code>. For example, <code>let obj = {name: "Ali", age: 25}</code>. This creates a collection of key-value pairs. To declare an empty object, use <code>{}</code>. You can add or modify keys using dot or bracket notation like <code>obj.name</code> or <code>obj["name"]</code>.</p>
                </div>
            </div>

            <div class="info-card">
                <div class="card-header"><h3>Boolean Logic (true && false)</h3></div>
                <div class="card-content">
                    <p>In JavaScript, the <code>&&</code> operator returns <code>true</code> only if both conditions are true. If even one side is false, the result is false. For example, <code>true && false</code> will return <code>false</code>. This is useful for writing complex conditions in if-statements or loops.</p>
                </div>
            </div>

            <div class="info-card">
                <div class="card-header"><h3>Ending Statements with Semicolons</h3></div>
                <div class="card-content">
                    <p>Semicolons <code>;</code> are used to indicate the end of a statement in JavaScript. While JavaScript can automatically insert semicolons in some cases, it’s a best practice to write them manually to avoid confusion or bugs—especially when writing multiple statements on the same line or complex logic.</p>
                </div>
            </div>

        </div>
    </div>

    <footer>
        <p>&copy; <?php echo date('Y'); ?> Codelab - JavaScript Quiz</p>
    </footer>

    <script>
        function startQuiz() {
            window.location.href = 'quiz.php';
        }
    </script>
</body>
</html>