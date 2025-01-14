<?php 
session_start();
require_once 'config/database.php';
require_once 'Class/User.php';

$db = new Database();
$pdo = $db->getConnection();
$user = new User($pdo);
$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    
    // Validation
    if (empty($email)) $errors[] = "Email is required";
    if (empty($password)) $errors[] = "Password is required";
    
    if (empty($errors)) {
        $result = $user->login($email, $password);
        
        if (is_array($result) && !isset($result['error'])) {
            // Set session variables
            $_SESSION['user_id'] = $result['id'];
            $_SESSION['user_name'] = $result['firstname'] . ' ' . $result['lastname'];
            $_SESSION['user_email'] = $result['email'];
            $_SESSION['user_role'] = $result['role'];
            
            // Redirect based on role
            switch($result['role']) {
                case 'admin':
                    header('Location: admin/index.php');
                    break;
                case 'teacher':
                    header('Location: teacher/index.php');
                    break;
                case 'student':
                    header('Location: student/index.php');
                    break;
                default:
                    header('Location: index.php');
            }
            exit();
        } else {
            $errors[] = isset($result['error']) ? $result['error'] : "Invalid email or password";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - Youdemy</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="bg-gradient">
    <div class="login-container">
        <h2>Welcome to Youdemy</h2>
        
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <?php foreach($errors as $error): ?>
                    <p><?php echo htmlspecialchars($error); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <?php 
                    echo htmlspecialchars($_SESSION['success']);
                    unset($_SESSION['success']);
                ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="auth.php">
            <div class="form-group">
                <input type="email" name="email" placeholder="Email" required>
            </div>
            
            <div class="form-group">
                <input type="password" name="password" placeholder="Password" required>
            </div>
            
            <button type="submit">Login</button>
            
            <p>Don't have an account? <a href="register.php">Register here</a></p>
        </form>
    </div>
</body>
</html>