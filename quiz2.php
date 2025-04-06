<?php
include 'db_connection.php';

// Fetch quiz questions from the database
$query = "SELECT * FROM quiz2_questions ORDER BY RAND() LIMIT 20";
$result = mysqli_query($conn, $query);

// Check if query was successful
if (!$result) {
    die("Database error: " . mysqli_error($conn));
}

$questions = [];

while ($row = mysqli_fetch_assoc($result)) {
    $correctOption = ord($row['correct_option']) - ord('A');
    
    $questions[] = [
        'id' => $row['id'],
        'question' => $row['question'],
        'options' => [$row['option_a'], $row['option_b'], $row['option_c'], $row['option_d']],
        'correctAnswer' => $correctOption,
        'explanation' => $row['explanation']
    ];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz</title>
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
        .container {
            max-width: 600px;
            margin: 50px auto;
            background: #d6d2bd;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .progress-bar {
            height: 5px;
            background: #a3b45c;
            width: 0%;
            margin-bottom: 20px;
        }
        .question {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 15px;
        }
        .options button {
            display: block;
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            background: #6d685f;
            border: none;
            color: white;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
            transition: background 0.3s;
        }
        .options button:hover {
            background: #5a5649;
        }
        .correct {
            background: #a3b45c !important;
        }
        .wrong {
            background: #9c3e3e !important;
        }
        .explanation {
            background:rgb(149, 166, 111);
            color: white;
            padding: 10px;
            margin-top: 10px;
            border-radius: 5px;
        }
        .next-btn {
            background: #36332d;
            padding: 10px;
            width: 100%;
            margin-top: 20px;
            border: none;
            color: white;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
        }
        .quiz-completed {
            text-align: center;
            padding: 20px;
        }
        .score {
            font-size: 24px;
            margin: 15px 0;
        }
        .restart-btn {
            background: #a3b45c;
            padding: 10px 20px;
            border: none;
            color: white;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
            margin-top: 15px;
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

    <div class="container">
        <div class="question" id="question">Loading question...</div>
        <div class="options" id="options"></div>
        <div class="explanation" id="explanation"></div>
        <button class="next-btn" id="nextBtn">Next Question</button>
    </div>

    <script>
        let currentQuestionIndex = 0;
        let score = 0;
        let questions = <?php echo json_encode($questions); ?>;

        //question section
        function displayQuestion() {
            if (currentQuestionIndex >= questions.length) {
                showQuizResults();;
                return;
            }

            let q = questions[currentQuestionIndex];
            document.getElementById('question').innerText = q.question;
            document.getElementById('options').innerHTML = "";
            document.getElementById('explanation').style.display = "none";
            document.getElementById('nextBtn').style.display = "none";

            q.options.forEach((option, index) => {
                let btn = document.createElement('button');
                btn.innerText = option;
                btn.onclick = () => checkAnswer(index);
                document.getElementById('options').appendChild(btn);
            });

            updateProgressBar();
        }

        //check answer
        function checkAnswer(selectedIndex) {
            let q = questions[currentQuestionIndex];
            let buttons = document.querySelectorAll('.options button');

            //answer correct and update score
            let isCorrect = selectedIndex === q.correctAnswer;
            if (isCorrect) {
                score++;
            }

            buttons.forEach((btn, index) => {
                //highlight correct answer in green
                if (index === q.correctAnswer) {
                    btn.classList.add('correct');
                } 
                //highlight wrong answer in red
                else if (index === selectedIndex && !isCorrect) {
                    btn.classList.add('wrong');
                }
                btn.disabled = true; // Disable all buttons after answer is selected
            });

            document.getElementById('explanation').innerText = q.explanation;
            document.getElementById('explanation').style.display = "block";
            document.getElementById('nextBtn').style.display = "block";
        }

        function updateProgressBar() {
            let progress = ((currentQuestionIndex + 1) / questions.length) * 100;
            document.getElementById('progress').style.width = progress + "%";
        }

        //show result
        function showQuizResults() {
            let container = document.querySelector('.container');
            container.innerHTML = `
                <div class="quiz-completed">
                    <h2>Quiz Completed!</h2>
                    <div class="score">Your Score: ${score}/${questions.length}</div>
                    <p>You answered ${score} out of ${questions.length} questions correctly.</p>
                    <button class="restart-btn" onclick="location.reload()">Take Quiz Again</button>
                </div>
            `;
        }

        document.getElementById('nextBtn').addEventListener('click', () => {
            currentQuestionIndex++;
            displayQuestion();
        });

        //strt quiz
        displayQuestion();
    </script>
</body>
</html>