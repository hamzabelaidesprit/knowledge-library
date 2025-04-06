<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/helpers.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name       = trim($_POST['name']);
    $email      = trim($_POST['email']);
    $password   = $_POST['password'];
    $speciality = $_POST['speciality'];

    if (!$name || !$email || !$password || !$speciality) {
        flash('error', 'All fields are required.');
        header('Location: register.php');
        exit;
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    try {
        $stmt = $pdo->prepare("INSERT INTO employees (name, email, password, speciality_id, is_admin) VALUES (?, ?, ?, ?, 0)");
        $stmt->execute([$name, $email, $hashedPassword, $speciality]);

        flash('success', 'Account created! You can now login.');
        header('Location: index.php');
        exit;
    } catch (PDOException $e) {
        flash('error', 'Email already registered.');
        header('Location: register.php');
        exit;
    }
}

// Get specialties
$specialties = $pdo->query("SELECT * FROM specialities")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register | Knowledge Library</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-body">
                        <h3 class="card-title text-center mb-4">Register</h3>

                        <?php if ($msg = flash('error')): ?>
                            <div class="alert alert-danger"><?= $msg ?></div>
                        <?php elseif ($msg = flash('success')): ?>
                            <div class="alert alert-success"><?= $msg ?></div>
                        <?php endif; ?>

                        <form method="POST">
                            <div class="mb-3">
                                <label>Name</label>
                                <input type="text" name="name" class="form-control" required />
                            </div>
                            <div class="mb-3">
                                <label>Email</label>
                                <input type="email" name="email" class="form-control" required />
                            </div>
                            <div class="mb-3">
                                <label>Password</label>
                                <input type="password" name="password" class="form-control" required />
                            </div>
                            <div class="mb-3">
                                <label>Speciality</label>
                                <select name="speciality" class="form-select" required>
                                    <option value="">-- Select Speciality --</option>
                                    <?php foreach ($specialties as $spec): ?>
                                        <option value="<?= $spec['id'] ?>"><?= htmlspecialchars($spec['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-success w-100">Register</button>
                        </form>

                        <div class="mt-3 text-center">
                            <small>Already have an account? <a href="index.php">Login here</a></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
