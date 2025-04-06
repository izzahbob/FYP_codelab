<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "shuuscript_db");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $password = $_POST["password"]; // Store password as plain text

    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $name, $email, $password);

    if ($stmt->execute()) {
        echo "<script>alert('Signup successful!'); window.location='login.php';</script>";
    } else {
        echo "<script>alert('Error: " . $stmt->error . "');</script>";
    }
    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Codelab</title>
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
            background-image: radial-gradient(circle at 90% 10%, rgba(163, 180, 92, 0.2) 0%, rgba(226, 223, 207, 0.8) 80%);
        }

        .signup-container {
            width: 100%;
            max-width: 450px;
            padding: 40px;
            background-color: white;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            position: relative;
            overflow: hidden;
        }

        .signup-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 8px;
            background-color: var(--color-accent);
        }

        .signup-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .signup-header h2 {
            font-size: 28px;
            color: var(--color-dark);
            margin-bottom: 10px;
        }

        .signup-header p {
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

        .login-link {
            text-align: center;
            color: var(--color-medium);
        }

        .login-link a {
            color: var(--color-accent);
            text-decoration: none;
            font-weight: 600;
            transition: var(--transition);
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        @media (max-width: 480px) {
            .signup-container {
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="signup-container">
        <div class="signup-header">
            <h2>Create an Account</h2>
            <p>Join our community of developers</p>
        </div>
        <form action="signup.php" method="POST">
            <div class="input-group">
                <label for="name">Full Name</label>
                <i class="fas fa-user"></i>
                <input type="text" id="name" name="name" placeholder="Enter your full name" required>
            </div>
            <div class="input-group">
                <label for="email">Email Address</label>
                <i class="fas fa-envelope"></i>
                <input type="email" id="email" name="email" placeholder="Enter your email" required>
            </div>
            <div class="input-group">
                <label for="password">Password</label>
                <i class="fas fa-lock"></i>
                <input type="password" id="password" name="password" placeholder="Create a password" required>
            </div>
            <button type="submit" class="submit-btn">Create Account</button>
        </form>
        <div class="divider">
            <span>OR</span>
        </div>
        <div class="login-link">
            <p>Already have an account? <a href="login.php">Sign In</a></p>
        </div>
    </div>
</body>
</html>