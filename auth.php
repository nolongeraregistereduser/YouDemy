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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            display: flex;
            min-height: 100vh;
            font-family: 'Inter', sans-serif;
            background: #f8f9fb;
        }

        .login-page {
            display: flex;
            width: 100%;
        }

        .login-image {
            flex: 1;
            background-image: url('assets/images/login-bg.jpg');
            background-size: cover;
            background-position: center;
        }

        .login-container {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 64px;
            max-width: 480px;
            margin: 0 auto;
        }

        h2 {
            font-size: 32px;
            color: #1c1d1f;
            margin-bottom: 48px;
            font-weight: 600;
        }

        .form-group {
            margin-bottom: 24px;
        }

        .form-group input {
            width: 100%;
            padding: 16px;
            border: 1px solid #d1d7dc;
            border-radius: 4px;
            font-size: 15px;
            transition: all 0.2s;
        }

        .form-group input:focus {
            border-color: #a435f0;
            outline: none;
            box-shadow: 0 0 0 2px rgba(164,53,240,0.1);
        }

        button {
            width: 100%;
            padding: 16px;
            background: #a435f0;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
            margin-top: 16px;
        }

        button:hover {
            background: #8710d8;
        }

        .divider {
            text-align: center;
            margin: 24px 0;
            position: relative;
        }

        .divider::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            width: 100%;
            height: 1px;
            background: #d1d7dc;
        }

        .divider span {
            background: #fff;
            padding: 0 16px;
            color: #6a6f73;
            position: relative;
            font-size: 14px;
        }

        .social-login {
            display: flex;
            gap: 16px;
            margin-bottom: 24px;
        }

        .social-btn {
            flex: 1;
            padding: 12px;
            border: 1px solid #d1d7dc;
            border-radius: 4px;
            background: white;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.2s;
        }

        .social-btn:hover {
            background: #f7f9fa;
            border-color: #a435f0;
        }

        .social-btn img {
            width: 20px;
            height: 20px;
        }

        p {
            text-align: center;
            color: #6a6f73;
            font-size: 14px;
            margin-top: 24px;
        }

        a {
            color: #a435f0;
            text-decoration: none;
            font-weight: 500;
        }

        a:hover {
            text-decoration: underline;
        }

        .alert {
            padding: 16px;
            margin-bottom: 24px;
            border-radius: 4px;
            font-size: 14px;
        }

        .alert-danger {
            background: #fff2f0;
            border: 1px solid #ffccc7;
            color: #cf1322;
        }

        .alert-success {
            background: #f6ffed;
            border: 1px solid #b7eb8f;
            color: #389e0d;
        }
    </style>
</head>
<body>
    <div class="login-page">
        <div class="login-image"></div>
        <div class="login-container">
            <h2>Connectez-vous à Youdemy</h2>
            
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
                    <input type="password" name="password" placeholder="Mot de passe" required>
                </div>
                
                <button type="submit">Se connecter</button>
            </form>
            
            <p>Vous n'avez pas de compte ? <a href="register.php">Inscrivez-vous</a></p>
        </div>
    </div>
</body>
</html>