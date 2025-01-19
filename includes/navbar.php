<?php
// Remove HTML, head, body tags - keep only content
?>
<div class="top-banner">
    Prêt à vous mettre à la page ? | Obtenez les compétences qu'il vous faut avec Udemy Business.
</div>
<div class="header">
    <div class="logo">
        <a href="/Youdemy/index.php">
            <img src="/Youdemy/assets/images/logo.png" alt="YouDemy Logo">
            YouDemy
        </a>
    </div>
    <div class="search-bar">
        <input type="text" placeholder="Rechercher un cours...">
        <i class="fas fa-search"></i>
    </div>
    <div class="nav-links">
        <?php if(!isset($_SESSION['user'])): ?>
            <a href="/Youdemy/auth.php" class="btn btn-login">Se connecter</a>
            <a href="/Youdemy/register.php" class="btn btn-register">S'inscrire</a>
        <?php else: ?>
            <a href="/Youdemy/courses.php">Tous les cours</a>
            <a href="/Youdemy/my-learning.php">Mes cours</a>
            <div class="user-menu">
                <span>
                    <i class="fas fa-user"></i>
                    <?php echo htmlspecialchars($_SESSION['user']['firstname']); ?>
                </span>
                <a href="/Youdemy/logout.php" class="btn btn-logout">
                    <i class="fas fa-sign-out-alt"></i>
                    Déconnexion
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>