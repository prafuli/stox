<?php
session_start();

// Include database connection
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: index.php?error=Invalid email format");
        exit;
    }

    // Prepare SQL query to fetch user by email
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Verify the password
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id']; 
            $_SESSION['user_email'] = $user['email']; // Optional: if you want to show email too
            header("Location: dashboard.php");
            exit;
        } else {
            header("Location: index.php?error=Invalid password");
            exit;
        }
    } else {
        header("Location: index.php?error=No user found with that email");
        exit;
    }

    $stmt->close();
    $conn->close();
}
?>
