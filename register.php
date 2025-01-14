<?php
session_start();
require_once 'config/database.php';
require_once 'Class/User.php';

$db = new Database();
$pdo = $db->getConnection();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user = new User($pdo);
    
    // Validation
    $data = [
        'firstname' => $_POST['firstname'] ?? '',
        'lastname' => $_POST['lastname'] ?? '',
        'email' => $_POST['email'] ?? '',
        'password' => $_POST['password'] ?? '',
        'role' => $_POST['role'] ?? '', // student/teacher
    ];
    
    // Validation rules
    $errors = [];
    if (empty($data['firstname'])) $errors[] = "First name is required";
    if (empty($data['lastname'])) $errors[] = "Last name is required";
    if (empty($data['email'])) $errors[] = "Email is required";
    if (empty($data['password'])) $errors[] = "Password is required";
    if (empty($data['role'])) $errors[] = "Role is required";
    
    if (empty($errors)) {
        if ($user->register($data)) {
            $_SESSION['success'] = "Registration successful! Please login.";
            header('Location: auth.php');
            exit();
        } else {
            $errors[] = "Registration failed";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register - Youdemy</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="bg-gradient">
    <div class="register-container">
        <h2>Create Account</h2>
        
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <?php foreach($errors as $error): ?>
                    <p><?php echo htmlspecialchars($error); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="register.php" onsubmit="validateRegisterForm(event)">
            <div class="form-group">
                <input type="text" name="firstname" placeholder="First Name" required
                       oninput="this.classList.remove('error')">
            </div>
            
            <div class="form-group">
                <input type="text" name="lastname" placeholder="Last Name" required
                       oninput="this.classList.remove('error')">
            </div>
            
            <div class="form-group">
                <input type="email" name="email" placeholder="Email" required
                       oninput="this.classList.remove('error')">
            </div>
            
            <div class="form-group">
                <input type="password" name="password" placeholder="Password" required
                       oninput="updatePasswordStrength(this.value); this.classList.remove('error')">
                <div id="password-strength"></div>
            </div>
            
            <div class="form-group">
                <select name="role" required onchange="this.classList.remove('error')">
                    <option value="">Select Role</option>
                    <option value="student">Student</option>
                    <option value="teacher">Teacher</option>
                </select>
            </div>
            
            <button type="submit">Register</button>
            
            <p>Already have an account? <a href="auth.php">Login here</a></p>
        </form>
    </div>
    <script src="assets/js/validation.js"></script>
</body>
</html> 