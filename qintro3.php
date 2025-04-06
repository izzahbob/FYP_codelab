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
                <div class="card-header"><h3>async Functions</h3></div>
                <div class="card-content">
                    <p>The <code>async</code> keyword makes a function return a Promise automatically. This enables the use of <code>await</code> inside the function, allowing asynchronous code to look and behave like synchronous code.</p>
                </div>
            </div>
            
            <div class="info-card">
                <div class="card-header"><h3>await Keyword</h3></div>
                <div class="card-content">
                    <p><code>await</code> pauses the execution of an async function until a Promise is resolved or rejected. It's only valid inside functions declared with <code>async</code>.</p>
                </div>
            </div>
            
            <div class="info-card">
                <div class="card-header"><h3>Closures</h3></div>
                <div class="card-content">
                    <p>A closure is a function that "remembers" the variables from its outer scope even after the outer function has finished executing. This is useful for creating private variables and function factories.</p>
                </div>
            </div>
            
            <div class="info-card">
                <div class="card-header"><h3>typeof NaN</h3></div>
                <div class="card-content">
                    <p>Even though NaN means "Not a Number", <code>typeof NaN</code> actually returns <code>"number"</code>. This is due to historical quirks in JavaScript's type system.</p>
                </div>
            </div>
            
            <div class="info-card">
                <div class="card-header"><h3>Understanding Promises</h3></div>
                <div class="card-content">
                    <p>Promises represent a value that might not be available yet but will be resolved in the future. They're useful for managing asynchronous operations like fetching data from an API.</p>
                </div>
            </div>
            
            <div class="info-card">
                <div class="card-header"><h3>Private Class Fields</h3></div>
                <div class="card-content">
                    <p>To declare private fields in a class, prefix the variable with <code>#</code>. These fields are only accessible within the class itself and cannot be accessed or modified from outside.</p>
                </div>
            </div>
            
            <div class="info-card">
                <div class="card-header"><h3>Object.freeze()</h3></div>
                <div class="card-content">
                    <p><code>Object.freeze()</code> prevents any changes to an object. You cannot add, delete, or modify properties of a frozen object. It's useful for creating immutable objects.</p>
                </div>
            </div>
            
            <div class="info-card">
                <div class="card-header"><h3>Type Coercion Example</h3></div>
                <div class="card-content">
                    <p>Expressions like <code>1 + "1" - 1</code> demonstrate JavaScript's automatic type coercion. <code>1 + "1"</code> becomes <code>"11"</code>, and then <code>"11" - 1</code> gives <code>10</code> because <code>-</code> forces the string back to a number.</p>
                </div>
            </div>
            
            <div class="info-card">
                <div class="card-header"><h3>fetch() and Promises</h3></div>
                <div class="card-content">
                    <p>The <code>fetch()</code> function is used to make network requests. It returns a Promise that resolves with the response of the request, making it essential in modern web apps.</p>
                </div>
            </div>
            
            <div class="info-card">
                <div class="card-header"><h3>Arrow Functions</h3></div>
                <div class="card-content">
                    <p>Arrow functions use a simpler syntax and do not bind their own <code>this</code>. This makes them ideal for callbacks where you want to inherit <code>this</code> from the surrounding scope.</p>
                </div>
            </div>
            
            <div class="info-card">
                <div class="card-header"><h3>this in Regular Functions</h3></div>
                <div class="card-content">
                    <p>In a regular function, <code>this</code> refers to the object that called the function. In the global context, it usually refers to <code>window</code> in the browser.</p>
                </div>
            </div>
            
            <div class="info-card">
                <div class="card-header"><h3>Object.assign()</h3></div>
                <div class="card-content">
                    <p><code>Object.assign()</code> copies all enumerable properties from one or more source objects to a target object. It’s often used to clone or merge objects.</p>
                </div>
            </div>
            
            <div class="info-card">
                <div class="card-header"><h3>Preventing Memory Leaks</h3></div>
                <div class="card-content">
                    <p>To avoid memory leaks in JavaScript, remove event listeners that are no longer needed, and avoid circular references or global variables that persist unnecessarily.</p>
                </div>
            </div>
            
            <div class="info-card">
                <div class="card-header"><h3>yield Keyword</h3></div>
                <div class="card-content">
                    <p>The <code>yield</code> keyword is used inside generator functions to pause execution and return a value. It resumes from where it left off when the generator is called again.</p>
                </div>
            </div>
            
            <div class="info-card">
                <div class="card-header"><h3>ES Modules</h3></div>
                <div class="card-content">
                    <p>Modern JavaScript uses ES Modules. You can use <code>import</code> and <code>export</code> to organize your code across multiple files. Example: <code>import { func } from "./utils.js"</code>.</p>
                </div>
            </div>
            
            <div class="info-card">
                <div class="card-header"><h3>const Keyword</h3></div>
                <div class="card-content">
                    <p><code>const</code> is used to declare variables that cannot be reassigned. However, for objects and arrays, their content can still be changed unless the object is frozen.</p>
                </div>
            </div>
            
            <div class="info-card">
                <div class="card-header"><h3>Event Loop</h3></div>
                <div class="card-content">
                    <p>The JavaScript event loop handles asynchronous code execution. It manages the call stack and message queue, ensuring that tasks like timeouts and promises execute properly without blocking the main thread.</p>
                </div>
            </div>
            
            <div class="info-card">
                <div class="card-header"><h3>Unhandled Promise Rejections</h3></div>
                <div class="card-content">
                    <p>If a Promise is rejected and not handled with <code>catch()</code>, it can cause an error that crashes the app or logs a warning. Always catch your errors!</p>
                </div>
            </div>
            
            <div class="info-card">
                <div class="card-header"><h3>Set vs Array</h3></div>
                <div class="card-content">
                    <p><code>Set</code> is a collection of unique values. Unlike arrays, it automatically removes duplicates and is useful when uniqueness is important.</p>
                </div>
            </div>
            
            <div class="info-card">
                <div class="card-header"><h3>Spread Operator on Strings</h3></div>
                <div class="card-content">
                    <p><code>[..."abc"]</code> spreads a string into an array of its characters: <code>["a", "b", "c"]</code>. This is a neat way to manipulate strings as arrays.</p>
                </div>
            </div>
            
            <div class="info-card">
                <div class="card-header"><h3>Shallow Copy</h3></div>
                <div class="card-content">
                    <p><code>Object.assign({}, obj)</code> creates a shallow copy of <code>obj</code>. It copies only the top-level properties, not nested ones.</p>
                </div>
            </div>
            
            <div class="info-card">
                <div class="card-header"><h3>Importing Functions (ES Modules)</h3></div>
                <div class="card-content">
                    <p>To import a function from another file, use <code>import { myFunc } from "./file.js"</code>. This is cleaner and more structured than older methods like <code>require()</code>.</p>
                </div>
            </div>
            
            <div class="info-card">
                <div class="card-header"><h3>WeakMap</h3></div>
                <div class="card-content">
                    <p><code>WeakMap</code> allows keys to be garbage-collected if there are no other references to them. It’s useful for memory-sensitive use cases like private data storage in classes.</p>
                </div>
            </div>
            
            <div class="info-card">
                <div class="card-header"><h3>Promise.all()</h3></div>
                <div class="card-content">
                    <p><code>Promise.all()</code> waits for all given promises to resolve or rejects as soon as one fails. It's great for running multiple async tasks in parallel and collecting their results.</p>
                </div>
            </div>
            
            <div class="info-card">
                <div class="card-header"><h3>Tail Call Optimization</h3></div>
                <div class="card-content">
                    <p>Tail Call Optimization (TCO) is a feature that reuses stack frames for recursive calls when the function returns its own call. It helps avoid stack overflows in deeply recursive functions (though not yet supported in all browsers).</p>
                </div>
            </div>
            
        </div>
    </div>

    <footer>
        <p>&copy; <?php echo date('Y'); ?> Codelab - JavaScript Quiz</p>
    </footer>

    <script>
        function startQuiz() {
            window.location.href = 'quiz3.php';
        }
    </script>
</body>
</html>