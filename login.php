<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/helpers.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (!$email || !$password) {
        flash('error', 'Email and password are required.');
        header('Location: index.php');
        exit;
    }

    // Check user in database
    $stmt = $pdo->prepare("SELECT * FROM employees WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        // Login successful - set session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['is_admin'] = $user['is_admin'];
        $_SESSION['speciality_id'] = $user['speciality_id'];

        // Redirect to proper dashboard
        if ($user['is_admin']) {
            header("Location: admin/dashboard.php");
        } else {
            header("Location: employee/dashboard.php");
        }
        exit;
    } else {
        flash('error', 'Invalid email or password.');
        header("Location: index.php");
        exit;
    }
} else {
    header("Location: index.php");
    exit;
}
