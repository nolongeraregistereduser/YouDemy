<?php 
session_start();
require_once 'config/database.php';
require_once 'Class/User.php';
require_once 'Class/Student.php';
require_once 'Class/Teacher.php';
require_once 'Class/Admin.php';

$db = new Database();
$pdo = $db->getConnection();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    try {
        // Check if user exists and get role and status
        $query = "SELECT id, role, status FROM users WHERE email = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$email]);
        $userData = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$userData) {
            $errors[] = "Email ou mot de passe invalide";
        } else {
            // Check status for teachers
            if ($userData['role'] === 'teacher' && $userData['status'] !== 'active') {
                $errors[] = "Votre compte enseignant est en attente d'approbation par un administrateur.";
            } else {
                // Create appropriate user instance
                switch ($userData['role']) {
                    case 'student':
                        $user = new Student($pdo);
                        break;
                    case 'teacher':
                        $user = new Teacher($pdo);
                        break;
                    case 'admin':
                        $user = new Admin($pdo);
                        break;
                    default:
                        $errors[] = "Type de compte invalide";
                        break;
                }

                if (isset($user)) {
                    $loginResult = $user->login($email, $password);
                    if (is_array($loginResult)) {
                        $_SESSION['user'] = $loginResult;
                        
                        // Updated redirections based on role
                        switch ($loginResult['role']) {
                            case 'admin':
                                header('Location: /Youdemy/admin/index.php');
                                break;
                            case 'teacher':
                                header('Location: /Youdemy/professor/index.php');
                                break;
                            case 'student':
                                header('Location: /Youdemy/index.php');  // Changed to main index for students
                                break;
                        }
                        exit();
                    } else {
                        $errors[] = "Email ou mot de passe invalide";
                    }
                }
            }
        }
    } catch (PDOException $e) {
        error_log("Login Error: " . $e->getMessage());
        $errors[] = "Une erreur s'est produite. Veuillez réessayer plus tard.";
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