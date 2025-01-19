<?php
session_start();
require_once 'config/database.php';
require_once 'Class/User.php';
require_once 'Class/Student.php';
require_once 'Class/Teacher.php';

$db = new Database();
$pdo = $db->getConnection();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $role = $_POST['role'] ?? '';
    if ($role === 'student') {
        $user = new Student($pdo);
    } elseif ($role === 'teacher') {
        $user = new Teacher($pdo);
    } else {
        $errors[] = "Invalid role selected";
    }
    
    if (isset($user)) {
        $data = [
            'firstname' => $_POST['firstname'] ?? '',
            'lastname' => $_POST['lastname'] ?? '',
            'email' => $_POST['email'] ?? '',
            'password' => $_POST['password'] ?? '',
        ];
        
        // Validation rules
        $errors = [];
        if (empty($data['firstname'])) $errors[] = "First name is required";
        if (empty($data['lastname'])) $errors[] = "Last name is required";
        if (empty($data['email'])) $errors[] = "Email is required";
        if (empty($data['password'])) $errors[] = "Password is required";
        
        if (empty($errors)) {
            if ($user->register($data)) {
                $_SESSION['success'] = "Registration successful! Please login.";
                header('Location: /Youdemy/auth.php');
                exit();
            } else {
                $errors[] = "Registration failed";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register - Youdemy</title>
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

        .register-page {
            display: flex;
            width: 100%;
        }

        .register-image {
            flex: 1;
            background-image: url('assets/images/register-bg.jpg');
            background-size: cover;
            background-position: center;
        }

        .register-container {
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

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 16px;
            border: 1px solid #d1d7dc;
            border-radius: 4px;
            font-size: 15px;
            transition: all 0.2s;
        }

        .form-group input:focus,
        .form-group select:focus {
            border-color: #a435f0;
            outline: none;
            box-shadow: 0 0 0 2px rgba(164,53,240,0.1);
        }

        .form-group select {
            appearance: none;
            background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23666' d='M6 8.825L1.175 4 2.238 2.938 6 6.7l3.763-3.763L10.825 4z'/%3E%3C/svg%3E") no-repeat right 15px center;
            background-color: white;
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
            width: 24px;
            height: 24px;
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
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

        #password-strength {
            font-size: 12px;
            margin-top: 8px;
            color: #6a6f73;
        }

        .error {
            border-color: #dc3545 !important;
        }
    </style>
</head>
<body>
    <div class="register-page">
        <div class="register-image"></div>
        <div class="register-container">
            <h2>Créer un compte</h2>
            
            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <?php foreach($errors as $error): ?>
                        <p><?php echo htmlspecialchars($error); ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="/Youdemy/register.php" onsubmit="validateRegisterForm(event)">
                <div class="form-group">
                    <input type="text" name="firstname" placeholder="Prénom" required
                           oninput="this.classList.remove('error')">
                </div>
                
                <div class="form-group">
                    <input type="text" name="lastname" placeholder="Nom" required
                           oninput="this.classList.remove('error')">
                </div>
                
                <div class="form-group">
                    <input type="email" name="email" placeholder="Email" required
                           oninput="this.classList.remove('error')">
                </div>
                
                <div class="form-group">
                    <input type="password" name="password" placeholder="Mot de passe" required
                           oninput="updatePasswordStrength(this.value); this.classList.remove('error')">
                    <div id="password-strength"></div>
                </div>
                
                <div class="form-group">
                    <select name="role" required onchange="this.classList.remove('error')">
                        <option value="">Sélectionnez votre rôle</option>
                        <option value="student">Étudiant</option>
                        <option value="teacher">Enseignant</option>
                    </select>
                </div>
                
                <button type="submit">S'inscrire</button>
            </form>
            
            <p>Vous avez déjà un compte ? <a href="/Youdemy/auth.php">Connectez-vous</a></p>
        </div>
    </div>
    <script src="/Youdemy/assets/js/validation.js"></script>
</body>
</html> 