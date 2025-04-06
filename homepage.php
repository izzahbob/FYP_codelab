<?php
// Start session to access session variables
session_start();

// Check if user is logged in, if not redirect to login page
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
include 'db_connection.php';
$user_id = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Codelab Homepage</title>
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

        /* Main Sections */
        .section-title {
            text-align: center;
            font-size: 28px;
            margin: 50px 0 30px;
            color: var(--color-dark);
        }

        /* Code Editor Section */
        .editor-section {
            background-color: var(--color-accent);
            padding: 80px 20px;
            text-align: center;
            margin: 0 auto;
            max-width: 1200px;
            border-radius: var(--radius);
        }

        .editor-title {
            font-size: 28px;
            margin-bottom: 20px;
            color: var(--color-dark);
        }

        .editor-desc {
            margin-bottom: 30px;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
            font-size: 16px;
        }

        /* Main Button Animation */
        .editor-section .btn {
            background-color: var(--color-dark);
            color: white;
            padding: 15px 40px;
            font-size: 16px;
            font-weight: 600;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            text-decoration: none;
            display: inline-block;
            transition: var(--transition);
            position: relative;
            overflow: hidden;
            z-index: 1;
        }

        .editor-section .btn:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }

        .editor-section .btn::before {
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

        .editor-section .btn:hover::before {
            left: 100%;
        }

        /* Exercises Section */
        .exercises-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .exercises-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
        }

        .exercise-card {
            display: flex;
            flex-direction: column;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            transition: var(--transition);
            transform: translateY(0);
        }

        .exercise-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
        }

        .exercise-icon {
            background-color: var(--color-medium);
            height: 150px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-top-left-radius: var(--radius);
            border-top-right-radius: var(--radius);
            transition: var(--transition);
        }

        .exercise-card:hover .exercise-icon {
            background-color: var(--color-dark);
        }

        .exercise-icon i, .exercise-icon svg {
            font-size: 50px;
            color: white;
            transition: var(--transition);
        }

        .exercise-card:hover .exercise-icon i {
            transform: scale(1.1);
        }

        .exercise-content {
            background-color: white;
            padding: 20px;
            border-bottom-left-radius: var(--radius);
            border-bottom-right-radius: var(--radius);
        }
        .exercise-content p {
            margin-bottom: 15px;
            color: var(--color-medium);
            font-size: 14px;
        }

        /* Exercise Card Button Animation */
        .exercise-content .btn {
            width: 100%;
            display: block;
            text-align: center;
            font-size: 14px;
            padding: 8px 0;
            background-color: var(--color-dark);
            color: white;
            text-decoration: none;
            border-radius: var(--radius);
            transition: var(--transition);
            font-weight: 500;
            position: relative;
            overflow: hidden;
            z-index: 1;
        }

        .exercise-content .btn:hover {
            background-color: var(--color-accent);
            color: var(--color-dark);
            font-weight: 600;
        }

        .exercise-content .btn::after {
            content: "→";
            position: absolute;
            right: -20px;
            opacity: 0;
            transition: 0.3s ease;
        }

        .exercise-content .btn:hover::after {
            right: 15px;
            opacity: 1;
        }

        .exercise-title {
            font-size: 18px;
            margin-bottom: 10px;
            color: var(--color-dark);
        }

        /* Quiz Section */
.quiz-section {
    margin: 60px auto;
    max-width: 1200px;
    padding: 0 20px;
}

.quiz-container {
    background-color: var(--color-medium);
    padding: 40px;
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    position: relative;
    overflow: hidden;
}

.quiz-container::before {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 150px;
    height: 150px;
    background-color: rgba(255, 255, 255, 0.05);
    border-radius: 50%;
    transform: translate(50%, -50%);
}

.quiz-title {
    color: white;
    font-size: 28px;
    margin-bottom: 30px;
    text-align: center;
    position: relative;
}

.quiz-title::after {
    content: '';
    display: block;
    width: 80px;
    height: 3px;
    background-color: var(--color-accent);
    margin: 15px auto 0;
}

.quiz-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 30px;
}

.quiz-card {
    background-color: white;
    padding: 30px;
    border-radius: var(--radius);
    text-align: center;
    transition: var(--transition);
    position: relative;
    display: flex;
    flex-direction: column;
    height: 100%;
}

.quiz-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
}

.quiz-level {
    width: 60px;
    height: 60px;
    background-color: var(--color-accent);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
    transition: var(--transition);
}

.quiz-card:hover .quiz-level {
    transform: scale(1.1);
}

.quiz-level i {
    font-size: 28px;
    color: var(--color-dark);
}

.quiz-card h4 {
    font-size: 22px;
    margin-bottom: 15px;
    color: var(--color-dark);
}

.quiz-card p {
    font-size: 14px;
    color: var(--color-medium);
    margin-bottom: 20px;
    line-height: 1.5;
}

.quiz-stats {
    display: flex;
    justify-content: center;
    gap: 20px;
    margin-bottom: 25px;
    font-size: 13px;
    color: var(--color-medium);
}

.quiz-stats span {
    display: flex;
    align-items: center;
    gap: 5px;
}

.quiz-stats i {
    color: var(--color-accent);
}

.quiz-btn {
    margin-top: auto;
    background-color: var(--color-dark);
    color: white;
    padding: 12px 20px;
    border-radius: var(--radius);
    text-decoration: none;
    font-weight: 500;
    transition: var(--transition);
    display: inline-block;
}

.quiz-btn:hover {
    background-color: var(--color-accent);
    color: var(--color-dark);
    font-weight: 600;
    transform: translateY(-3px);
}

/* Animation for quiz card on hover */
@keyframes pulse-subtle {
    0% { box-shadow: 0 0 0 0 rgba(163, 180, 92, 0.4); }
    70% { box-shadow: 0 0 0 10px rgba(163, 180, 92, 0); }
    100% { box-shadow: 0 0 0 0 rgba(163, 180, 92, 0); }
}

.quiz-card:hover {
    animation: pulse-subtle 1.5s infinite;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .quiz-container {
        padding: 30px 20px;
    }
    
    .quiz-grid {
        grid-template-columns: 1fr;
        gap: 20px;
    }
}

        .footer {
            background-color: var(--color-dark);
            color: white;
            text-align: center;
            padding: 20px;
            margin-top: 50px;
        }

        @media (max-width: 992px) {
            .exercises-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 576px) {
            .exercises-grid {
                grid-template-columns: 1fr;
            }
            
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
        }

        /* Add keyframe animations */
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        /* Add pulsing animation to main button on page load */
        .editor-section .btn {
            animation: pulse 2s infinite;
        }

        .editor-section .btn:hover {
            animation: none;
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
                <li><a href="profile.php">Profile</a></li>
            </ul>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        <!-- Start Coding Now Title -->
        <h1 class="section-title">Start Coding Now</h1>
        
        <!-- Code Editor Section -->
        <section class="editor-section">
            <h2 class="editor-title">Interactive Code Editor</h2>
            <p class="editor-desc">Write, test, and debug your code in our powerful online environment</p>
            <a href="editor.php" class="btn">Launch Editor</a>
        </section>
        
        <!-- Exercises Section -->
        <h2 class="section-title">Practice Exercises</h2>
        <div class="exercises-container">
            <div class="exercises-grid">

                <div class="exercise-card">
                    <div class="exercise-icon">
                        <i class="fas fa-terminal"></i>
                    </div>
                    <div class="exercise-content">
                        <h3 class="exercise-title">Java Basics</h3>
                        <p>Write a program to print 'Hello, World!'</p>
                        <a href="E1.php" class="btn">Start Exercise</a>
                    </div>
                </div>
                
                <div class="exercise-card">
                    <div class="exercise-icon">
                        <i class="fas fa-random"></i>
                    </div>
                    <div class="exercise-content">
                        <h3 class="exercise-title">Swap Two Variables</h3>
                        <p>Swap the values of two variables without using a third variable.</p>
                        <a href="E2.php" class="btn">Start Exercise</a>
                    </div>
                </div>
                
                <div class="exercise-card">
                    <div class="exercise-icon">
                        <i class="fas fa-calculator"></i>
                    </div>
                    <div class="exercise-content">
                        <h3 class="exercise-title">Multiply Without *</h3>
                        <p>Write a program to multiply two numbers without the '*' operator.</p>
                        <a href="E3.php" class="btn">Start Exercise</a>
                    </div>
                </div>
                
                <div class="exercise-card">
                    <div class="exercise-icon">
                        <i class="fas fa-search"></i>
                    </div>
                    <div class="exercise-content">
                        <h3 class="exercise-title">Find Missing Number</h3>
                        <p>Given an array of numbers, find the missing number.</p>
                        <a href="E4.php" class="btn">Start Exercise</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quiz Section -->
        <section class="quiz-section">
            <h2 class="section-title">Test Your Knowledge</h2>
            <div class="quiz-container">
                <h3 class="quiz-title">Interactive Quizzes</h3>
                <div class="quiz-grid">
                    <div class="quiz-card">
                        <div class="quiz-level">
                            <i class="fas fa-seedling"></i>
                        </div>
                        <h4>Beginners</h4>
                        <p>Perfect for those just starting their coding journey</p>
                        <div class="quiz-stats">
                            <span><i class="fas fa-question-circle"></i> 15 questions</span>
                        </div>
                        <a href="qintro.php" class="quiz-btn">Take Quiz</a>
                    </div>
                    <div class="quiz-card">
                        <div class="quiz-level">
                            <i class="fas fa-tree"></i>
                        </div>
                        <h4>Intermediate</h4>
                        <p>Challenge yourself with more complex concepts</p>
                        <div class="quiz-stats">
                            <span><i class="fas fa-question-circle"></i> 20 questions</span>
                        </div>
                        <a href="qintro2.php" class="quiz-btn">Take Quiz</a>
                    </div>
                    <div class="quiz-card">
                        <div class="quiz-level">
                            <i class="fas fa-mountain"></i>
                        </div>
                        <h4>Advanced</h4>
                        <p>Test your expertise with advanced programming challenges</p>
                        <div class="quiz-stats">
                            <span><i class="fas fa-question-circle"></i> 25 questions</span>
                        </div>
                        <a href="qintro3.php" class="quiz-btn">Take Quiz</a>
                    </div>
                </div>
            </div>
        </section>

    </main>
    
    <footer class="footer">
        <p>&copy; 2025 Codelab. All rights reserved.</p>
    </footer>
</body>
</html>