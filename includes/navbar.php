<?php
// Remove HTML, head, body tags - keep only content
?>
<div class="top-banner" style="text-align: center; padding: 10px; background-color: #1a237e; color: #ffffff; font-size: 0.9rem; font-weight: 500; letter-spacing: 0.3px;">
    Développez vos compétences avec Youdemy | Accédez à plus de 1000 cours en ligne de qualité
</div>
<div class="header" style="background-color: #ffffff; border-bottom: 1px solid #e0e0e0;">
    <div class="logo">
        <a href="/Youdemy/index.php" style="text-decoration: none; font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 1.8rem; color: #1a237e;">
            Youdemy
        </a>
    </div>
    <div class="search-bar" style="max-width: 400px; margin: 0 20px;">
        <input type="text" placeholder="Rechercher un cours..." style="width: 100%; padding: 8px 35px 8px 15px; border: 1px solid #e0e0e0; border-radius: 20px; font-size: 0.9rem; background-color: #f5f5f5;">
        <i class="fas fa-search" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); color: #757575;"></i>
    </div>
    <div class="nav-links" style="display: flex; align-items: center; gap: 20px;">
        <?php if(!isset($_SESSION['user'])): ?>
            <a href="/Youdemy/auth.php" class="btn btn-login" style="padding: 8px 20px; font-weight: 500; color: #1a237e; border: 1px solid #1a237e; border-radius: 4px;">Se connecter</a>
            <a href="/Youdemy/register.php" class="btn btn-register" style="padding: 8px 20px; font-weight: 500; background-color: #1a237e; color: white; border-radius: 4px; transition: all 0.3s ease;">S'inscrire</a>
        <?php else: ?>
            <a href="/Youdemy/courses.php" style="color: #424242; font-weight: 500; transition: color 0.3s ease;">Tous les cours</a>
            <a href="/Youdemy/my-learning.php" style="color: #424242; font-weight: 500; transition: color 0.3s ease;">Mes cours</a>
            <div class="user-menu" style="display: flex; align-items: center; gap: 10px;">
                <span style="display: flex; align-items: center; gap: 5px;">
                    <i class="fas fa-user" style="color: #1a237e;"></i>
                    <?php echo htmlspecialchars($_SESSION['user']['firstname']); ?>
                </span>
                <a href="/Youdemy/logout.php" class="btn btn-logout" style="display: flex; align-items: center; gap: 5px; color: #757575; transition: color 0.3s ease;">
                    <i class="fas fa-sign-out-alt"></i>
                    Déconnexion
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>