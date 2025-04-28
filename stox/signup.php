<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php'); // Redirect to dashboard if already logged in
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up | Stox</title>
    <link rel="stylesheet" href="style.css">
    <style>
        input[type="password"], input[type="email"] {
            padding: 10px;
            width: 100%;
            margin: 8px 0;
            border-radius: 4px;
            border: 1px solid #ccc;
        }

        .continue-btn {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .continue-btn:hover {
            background-color: #45a049;
        }

        .alert {
            color: red;
            margin-bottom: 10px;
        }

        .terms {
            font-size: 12px;
            color: #888;
        }

        .terms a {
            color: #4285F4;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="left">
            <h1>Simple, Free Investing.</h1>
        </div>
        <div class="right">
            <h2>Sign Up for Stox</h2>

            <?php if (isset($_GET['error'])): ?>
                <div class="alert error"><?= htmlspecialchars($_GET['error']) ?></div>
            <?php endif; ?>

            <form action="signup_action.php" method="POST">
                <input type="email" name="email" placeholder="Your Email Address" required>
                <input type="password" name="password" placeholder="Your Password" required>
                <input type="password" name="confirm_password" placeholder="Confirm Password" required>
                <button type="submit" class="continue-btn">Sign Up</button>
            </form>

            <p class="terms">
                By proceeding, I agree to <a href="#">T&C</a>, <a href="#">Privacy Policy</a> & <a href="#">Tariff Rates</a>
            </p>

            <div class="signup-link">
                <p>Already have an account? <a href="index.php">Login</a></p>
            </div>
        </div>
    </div>

    <script src="script.js"></script>
</body>
</html>
