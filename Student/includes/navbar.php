<html>
<head>
    <title>YouDemy Header</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styleStudent.css">
</head>
<body>
    <div class="top-banner">
        Prêt à vous mettre à la page ? | Obtenez les compétences qu'il vous faut avec Udemy Business.
    </div>
    <div class="header">
        <div class="logo">
            <a href="../index.php">
                <img src="../assets/images/logo.png" alt="YouDemy Logo">
                YouDemy
            </a>
        </div>
        <div class="search-bar">
            <input type="text" placeholder="Rechercher un cours...">
            <i class="fas fa-search"></i>
        </div>
        <div class="nav-links">
            <?php if(!isset($_SESSION['user'])): ?>
                <!-- Show for non-logged in users -->
                <a href="../auth.php" class="btn btn-login">Se connecter</a>
                <a href="../register.php" class="btn btn-register">S'inscrire</a>
            <?php else: ?>
                <!-- Show for logged in users -->
                <a href="courses.php">Tous les cours</a>
                <a href="my-learning.php">Mes cours</a>
                <div class="user-menu">
                    <span>
                        <i class="fas fa-user"></i>
                        <?php echo htmlspecialchars($_SESSION['user']['firstname']); ?>
                    </span>
                    <a href="../logout.php" class="btn btn-logout">
                        <i class="fas fa-sign-out-alt"></i>
                        Déconnexion
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>