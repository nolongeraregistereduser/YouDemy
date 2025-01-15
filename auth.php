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
    
    // On va d'abord vérifier dans la base de données pour déterminer le rôle
    $query = "SELECT role FROM users WHERE email = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$email]);
    $userRole = $stmt->fetchColumn();

    // Créer l'instance appropriée selon le rôle
    if ($userRole === 'student') {
        $user = new Student($pdo);
    } elseif ($userRole === 'teacher') {
        $user = new Teacher($pdo);
    } elseif ($userRole === 'admin') {
        $user = new Admin($pdo);
    } else {
        $errors[] = "Invalid user role";
    }

    if (isset($user)) {
        $loginResult = $user->login($email, $password);
        if (is_array($loginResult)) {
            $_SESSION['user'] = $loginResult;
            // Rediriger vers le bon dashboard selon le rôle
            if ($loginResult['role'] === 'admin') {
                header('Location: admin/index.php');
            } elseif ($loginResult['role'] === 'teacher') {
                header('Location: professor/index.php');
            } elseif ($loginResult['role'] === 'student') {
                header('Location: student/index.php');
            }

            // adding commits
            
            exit();
        } else {
            // Ajouter un message d'erreur spécifique pour les comptes en attente
            if ($userRole === 'teacher') {
                $errors[] = "Votre compte enseignant est en attente d'approbation par un administrateur.";
            } else {
                $errors[] = "Email ou mot de passe invalide";
            }
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