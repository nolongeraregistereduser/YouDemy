<?php
session_start();
?>
<html>
 <head>
  <title>
   Course Listings
  </title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet"/>
   <link rel="stylesheet" href="includes/styleStudent.css">
 </head>
 <body>
  <?php include 'includes/navbar.php'; ?>
  
  <!-- New Hero Section -->
  <div class="hero-container">
   <div class="text-content">
    <h1>Apprenez de nouvelles compétences en ligne avec YouDemy</h1>
    <p>Découvrez des milliers de cours enseignés par des experts de l'industrie. Commencez votre parcours d'apprentissage dès aujourd'hui.</p>
    <a href="#">Commencer maintenant</a>
   </div>
   <div class="image-content">
    <img src="https://storage.googleapis.com/a1aa/image/online-learning-illustration.jpg" alt="Online Learning">
   </div>
  </div>

  <div class="learning-goals-section">
    <div class="learning-goals-container">
        <div class="left-section">
            <h2 class="section-title">Pourquoi choisir YouDemy ?</h2>
            
            <div class="feature-card">
                <div class="card-title">
                    <i class="fas fa-laptop-code"></i>
                    Apprentissage Interactif
                </div>
                <div class="card-content">
                    Développez vos compétences avec des cours vidéo de haute qualité, des exercices pratiques et des projets réels guidés par des experts de l'industrie.
                </div>
            </div>

            <div class="feature-card">
                <div class="card-title">
                    <i class="fas fa-certificate"></i>
                    Certificats Reconnus
                </div>
                <div class="card-content">
                    Obtenez des certificats validés par l'industrie pour booster votre CV et votre carrière professionnelle.
                </div>
                <a class="card-link" href="#">
                    Découvrir les cours <i class="fas fa-arrow-right"></i>
                </a>
            </div>

            <div class="feature-card">
                <div class="card-title">
                    <i class="fas fa-chart-line"></i>
                    Suivi Personnalisé
                    <span class="premium-badge">Premium</span>
                </div>
                <div class="card-content">
                    Suivez votre progression et recevez des recommandations personnalisées basées sur vos objectifs d'apprentissage.
                </div>
                <a class="card-link" href="#">
                    En savoir plus <i class="fas fa-arrow-right"></i>
                </a>
            </div>

            <div class="feature-card">
                <div class="card-title">
                    <i class="fas fa-users"></i>
                    Communauté Active
                    <span class="premium-badge">Premium</span>
                </div>
                <div class="card-content">
                    Rejoignez une communauté dynamique d'apprenants et d'experts. Participez à des forums de discussion et des sessions de mentorat.
                </div>
                <a class="card-link" href="#">
                    Rejoindre <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>

        <div class="right-section">
            <div class="preview-container">
                <div class="preview-header">
                    <div class="title">Aperçu d'un cours</div>
                </div>
                <div class="preview-content">
                    <img src="https://storage.googleapis.com/a1aa/image/QswWHTWh8p5qDAtfFvOoZ4-hk3ChPuV2dck6qEk9me8.jpg" 
                         alt="Exemple de cours YouDemy" />
                </div>
            </div>
        </div>
    </div>
  </div>

  <div class="container">
   <div class="category-tabs">
    <div class="category-tab active">
     ChatGPT
     <br/>
     <span>
      + 4 M de participants
     </span>
    </div>
    <div class="category-tab">
     Science des données
     <br/>
     <span>
      + 7 M de participants
     </span>
    </div>
    <div class="category-tab">
     Python
     <br/>
     <span>
      + 46,6 M de participants
     </span>
    </div>
    <div class="category-tab">
     Machine Learning
     <br/>
     <span>
      + 8 M de participants
     </span>
    </div>
    <div class="category-tab">
     Deep Learning
     <br/>
     <span>
      + 2 M de participants
     </span>
    </div>
    <div class="category-tab">
     Intelligence artificielle (IA)
     <br/>
     <span>
      + 3 M de participants
     </span>
    </div>
   </div>
   <div class="course-list">
    <div class="course-card">
     <img alt="Image of a futuristic AI robot" height="400" src="https://storage.googleapis.com/a1aa/image/KLLepW1uPE_tlyDZhAc4lERLY3jZsvIvodnMqsXagtw.jpg" width="600"/>
     <div class="course-content">
      <div class="course-title">
       ChatGPT, GPT4, Midjourney | Formation IA Complète 2025
      </div>
      <div class="course-author">
       Jonathan Roux | IA ChatGPT | Python
      </div>
      <div class="course-badge">
       Meilleure vente
      </div>
      <a class="enroll-btn" href="#">
       Enroll This Course
      </a>
     </div>
    </div>
    <div class="course-card">
     <img alt="Image of a person with ChatGPT logo" height="400" src="https://storage.googleapis.com/a1aa/image/AKjHUrF-rX4WBVRt-2ew1IwaZnztXm-ICdVNfqbOUic.jpg" width="600"/>
     <div class="course-content">
      <div class="course-title">
       ChatGPT &amp; IA : Formation complète ChatGPT, Dall-e
      </div>
      <div class="course-author">
       Yassine Rochd
      </div>
      <div class="course-badge">
       Les mieux notés
      </div>
      <a class="enroll-btn" href="#">
       Enroll This Course
      </a>
     </div>
    </div>
    <div class="course-card">
     <img alt="Image of a hacker with AI brain illustration" height="400" src="https://storage.googleapis.com/a1aa/image/vLG32hjb-T0-As3LYqk8PKIsaoqxQsgs5lx3nhP6TsI.jpg" width="600"/>
     <div class="course-content">
      <div class="course-title">
       ChatGPT Hacking Éthique : Le Cours Complet
      </div>
      <div class="course-author">
       Dr. Firas | Europe Innovation
      </div>
      <a class="enroll-btn" href="#">
       Enroll This Course
      </a>
     </div>
    </div>
    <div class="course-card">
     <img alt="Image of ChatGPT and automation icons" height="400" src="https://storage.googleapis.com/a1aa/image/VkbpBzSkqUgA6rVirCErIlPCJOUB-dhMTTuq9EkQEz0.jpg" width="600"/>
     <div class="course-content">
      <div class="course-title">
       Guide ChatGPT : Prompts, Bots et Automatisation (MAJ 2024)
      </div>
      <div class="course-author">
       Dr. Firas | Europe Innovation
      </div>
      <div class="course-badge">
       Meilleure vente
      </div>
      <a class="enroll-btn" href="#">
       Enroll This Course
      </a>
     </div>
    </div>
   </div>
   <a class="view-all-btn" href="#">
    Afficher tous les cours de la catégorie Science des données
   </a>
  </div>

  <?php include 'includes/footer.php'; ?>
 </body>
</html>