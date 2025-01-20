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
   <link rel="stylesheet" href="assets/css/styleStudent.css">
   <link rel="stylesheet" href="assets/css/course-cards.css">
 </head>
 <body>
  <?php include 'includes/navbar.php'; ?>
  
  <!-- New Hero Section -->
  <div class="hero-container">
   <div class="text-content">
    <h1>Apprenez de nouvelles compétences en ligne avec YouDemy</h1>
    <p>Découvrez des milliers de cours enseignés par des experts de l'industrie. Commencez votre parcours d'apprentissage dès aujourd'hui.</p>
    <a href="courses.php">Commencer maintenant</a>
   </div>
   <div class="image-content">
    <img src="https://img.freepik.com/free-vector/online-tutorials-concept_52683-37480.jpg" 
         alt="Online Learning Illustration">
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
       En savoir plus
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
       En savoir plus
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
       En savoir plus
      </a>
     </div>
    </div>
   </div>
   <a class="view-all-btn" href="courses.php">
    Afficher tous les cours de la catégorie Science des données
   </a>
  </div>

  <!-- Testimonials Section -->
  <div class="testimonials-section">
    <div class="testimonials-container">
        <h2 class="testimonials-title">Ce que disent nos apprenants</h2>
        <div class="testimonials-grid">
            <div class="testimonial-card">
                <div class="quote-icon">
                    <i class="fas fa-quote-right"></i>
                </div>
                <p class="testimonial-text">
                    Grâce à YouDemy, j'ai pu acquérir les compétences nécessaires en Intelligence Artificielle et ChatGPT. Aujourd'hui, je travaille comme consultant IA dans une grande entreprise.
                </p>
                <div class="testimonial-author">
                    <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Mohamed Alami">
                    <div class="author-info">
                        <div class="author-name">Mohamed Alami</div>
                        <div class="author-title">Consultant en IA</div>
                    </div>
                </div>
                <a href="courses.php?category=1" class="testimonial-link">
                    Découvrir les cours d'IA <i class="fas fa-arrow-right"></i>
                </a>
            </div>

            <div class="testimonial-card">
                <div class="quote-icon">
                    <i class="fas fa-quote-right"></i>
                </div>
                <p class="testimonial-text">
                    La qualité des cours de data science sur YouDemy est exceptionnelle. Les instructeurs sont des experts du domaine et le contenu est très pratique.
                </p>
                <div class="testimonial-author">
                    <img src="https://randomuser.me/api/portraits/women/44.jpg" alt="Sarah Bennis">
                    <div class="author-info">
                        <div class="author-name">Sarah Bennis</div>
                        <div class="author-title">Data Scientist</div>
                    </div>
                </div>
                <a href="courses.php?category=2" class="testimonial-link">
                    Explorer les cours de Data Science <i class="fas fa-arrow-right"></i>
                </a>
            </div>

            <div class="testimonial-card">
                <div class="quote-icon">
                    <i class="fas fa-quote-right"></i>
                </div>
                <p class="testimonial-text">
                    J'ai commencé de zéro en programmation Python. Après 6 mois sur YouDemy, j'ai décroché mon premier emploi en tant que développeur junior.
                </p>
                <div class="testimonial-author">
                    <img src="https://randomuser.me/api/portraits/men/67.jpg" alt="Karim Idrissi">
                    <div class="author-info">
                        <div class="author-name">Karim Idrissi</div>
                        <div class="author-title">Développeur Python</div>
                    </div>
                </div>
                <a href="courses.php?category=3" class="testimonial-link">
                    Voir les cours Python <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
  </div>

  <?php include 'includes/footer.php'; ?>
 </body>
</html>

<style>
.testimonials-section {
    background-color: #f8f9fa;
    padding: 80px 0;
    margin: 40px 0;
}

.testimonials-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

.testimonials-title {
    text-align: center;
    font-size: 2.5rem;
    color: #2c3e50;
    margin-bottom: 50px;
    font-weight: 600;
}

.testimonials-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 30px;
    padding: 20px 0;
}

.testimonial-card {
    background: white;
    border-radius: 15px;
    padding: 30px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease;
    display: flex;
    flex-direction: column;
    height: 380px;
}

.testimonial-card:hover {
    transform: translateY(-5px);
}

.quote-icon {
    color: #3498db;
    font-size: 24px;
    margin-bottom: 20px;
}

.testimonial-text {
    flex-grow: 1;
    font-size: 1rem;
    line-height: 1.6;
    color: #4a5568;
    margin-bottom: 20px;
}

.testimonial-author {
    display: flex;
    align-items: center;
    margin-bottom: 20px;
}

.testimonial-author img {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    margin-right: 15px;
    object-fit: cover;
}

.author-info {
    flex-grow: 1;
}

.author-name {
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 4px;
}

.author-title {
    font-size: 0.9rem;
    color: #718096;
}

.testimonial-link {
    color: #3498db;
    text-decoration: none;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: color 0.3s ease;
}

.testimonial-link:hover {
    color: #2980b9;
}

.testimonial-link i {
    font-size: 0.8rem;
}

@media (max-width: 992px) {
    .testimonials-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .testimonials-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 480px) {
    .testimonials-section {
        padding: 40px 0;
    }
    
    .testimonial-card {
        padding: 20px;
    }
}
</style>