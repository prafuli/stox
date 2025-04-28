<?php
session_start();
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: signup.php?error=Invalid email format");
        exit;
    }

    if (strlen($password) < 6) {
        header("Location: signup.php?error=Password must be at least 6 characters");
        exit;
    }

    if ($password !== $confirm_password) {
        header("Location: signup.php?error=Passwords do not match");
        exit;
    }

    // Check if email already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->close();
        $conn->close();
        header("Location: signup.php?error=Email already registered");
        exit;
    }
    $stmt->close(); // close previous statement

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare INSERT query
    $stmt = $conn->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $email, $hashed_password);

    if ($stmt->execute()) {
        // Registration successful
        $_SESSION['user_id'] = $stmt->insert_id;
        $_SESSION['user_email'] = $email;
        
        echo "User added successfully!";
        $stmt->close();
        $conn->close();
        exit;
    } else {
        // If INSERT failed, show error
        echo "Error: " . $stmt->error;
        $stmt->close();
        $conn->close();
        exit;
    }
}
?>
