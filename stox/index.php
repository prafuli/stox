<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php'); 
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple, Free Investing | Stox</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="left">
            <h1>Simple, Free Investing.</h1>
        </div>
        <div class="right">
            <h2>Welcome to Stox</h2>

            <button class="google-btn" onclick="handleGoogleSignIn()">
                <img src="https://upload.wikimedia.org/wikipedia/commons/5/53/Google_%22G%22_Logo.svg" class="google-icon" alt="Google">
                Continue with Google
            </button>

            <div class="divider">OR</div>

            <!-- Email and Password Login Form -->
            <form action="auth.php" method="POST">
                <input type="email" name="email" placeholder="Your Email Address" required>
                <input type="password" name="password" placeholder="Your Password" required>
                <button type="submit" class="continue-btn">Continue</button>
            </form>

            <!-- Sign Up Link -->
            <div class="signup-link">
                <p>Don't have an account? <a href="signup.php">Sign Up</a></p> <!-- Link to the signup page -->
            </div>

            <p class="terms">
                By proceeding, I agree to <a href="#">T&C</a>, <a href="#">Privacy Policy</a> & <a href="#">Tariff Rates</a>
            </p>
        </div>
    </div>

    <script src="script.js"></script>
</body>
</html>
