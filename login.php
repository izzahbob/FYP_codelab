<?php
session_start();
include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password']; // Store and compare as plain text

    $sql = "SELECT * FROM users WHERE email=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if ($password === $user['password']) { 
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            header("Location: homepage.php");
            exit();
        } else {
            echo "<script>alert('Incorrect password!'); window.location='login.php';</script>";
            exit();
        }
    } else {
        echo "<script>alert('User not found!'); window.location='login.php';</script>";
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Codelab</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <style>
        :root {
            --color-bg: #e2dfcf;
            --color-dark: #36332d;
            --color-medium: #6d685f;
            --color-accent: #a3b45c;
            --font-main: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            --shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
            --radius: 10px;
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
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            background-image: radial-gradient(circle at 10% 20%, rgba(163, 180, 92, 0.2) 0%, rgba(226, 223, 207, 0.8) 80%);
        }

        .login-container {
            width: 100%;
            max-width: 450px;
            padding: 40px;
            background-color: white;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            position: relative;
            overflow: hidden;
        }

        .login-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 8px;
            background-color: var(--color-accent);
        }

        .login-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .login-header h2 {
            font-size: 28px;
            color: var(--color-dark);
            margin-bottom: 10px;
        }

        .login-header p {
            color: var(--color-medium);
            font-size: 16px;
        }

        .input-group {
            margin-bottom: 25px;
            position: relative;
        }

        .input-group label {
            display: block;
            margin-bottom: 8px;
            font-size: 16px;
            color: var(--color-dark);
            font-weight: 500;
        }

        .input-group input {
            width: 100%;
            padding: 15px 15px 15px 45px;
            border: 1px solid #ddd;
            border-radius: var(--radius);
            font-size: 16px;
            transition: var(--transition);
            background-color: #f9f9f9;
            color: var(--color-dark);
        }

        .input-group input:focus {
            outline: none;
            border-color: var(--color-accent);
            box-shadow: 0 0 0 3px rgba(163, 180, 92, 0.2);
            background-color: white;
        }

        .input-group i {
            position: absolute;
            top: 42px;
            left: 15px;
            color: var(--color-medium);
            font-size: 18px;
        }

        .submit-btn {
            width: 100%;
            padding: 15px;
            background-color: var(--color-accent);
            color: white;
            border: none;
            border-radius: var(--radius);
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            margin-bottom: 30px;
        }

        .submit-btn:hover {
            background-color: #8fa349;
            transform: translateY(-2px);
        }

        .divider {
            display: flex;
            align-items: center;
            margin: 30px 0;
        }

        .divider::before, .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background-color: #ddd;
        }

        .divider span {
            padding: 0 15px;
            color: var(--color-medium);
            font-size: 14px;
        }

        .signup-link {
            text-align: center;
            color: var(--color-medium);
        }

        .signup-link a {
            color: var(--color-accent);
            text-decoration: none;
            font-weight: 600;
            transition: var(--transition);
        }

        .signup-link a:hover {
            text-decoration: underline;
        }

        @media (max-width: 480px) {
            .login-container {
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h2>Welcome to Codelab</h2>
            <p>Sign in to continue your learning journey</p>
        </div>
        <form action="login.php" method="POST">
            <div class="input-group">
                <label for="email">Email Address</label>
                <i class="fas fa-envelope"></i>
                <input type="email" id="email" name="email" placeholder="Enter your email" required>
            </div>
            <div class="input-group">
                <label for="password">Password</label>
                <i class="fas fa-lock"></i>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
            </div>
            <button type="submit" class="submit-btn">Log In</button>
        </form>
        <div class="divider">
            <span>OR</span>
        </div>
        <div class="signup-link">
            <p>Don't have an account? <a href="signup.php">Create account</a></p>
        </div>
    </div>
</body>
</html>