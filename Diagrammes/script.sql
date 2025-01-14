-- Création des tables de base de données

-- Table pour les utilisateurs
CREATE TABLE utilisateurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    mot_de_passe VARCHAR(255) NOT NULL,
    role_id INT NOT NULL,  -- Référence à la table des rôles
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (role_id) REFERENCES roles(id)
);


-- kk--
-- Table pour les rôles
CREATE TABLE roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom_role VARCHAR(50) NOT NULL
);

-- Insérer des rôles par défaut
INSERT INTO roles (nom_role) VALUES
('Visiteur'),
('Étudiant'),
('Enseignant'),
('Administrateur');

-- Table pour les cours
CREATE TABLE cours (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(255) NOT NULL,
    description TEXT,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    enseignant_id INT,
    FOREIGN KEY (enseignant_id) REFERENCES utilisateurs(id)
);

-- Table pour les inscriptions des étudiants aux cours
CREATE TABLE inscriptions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    etudiant_id INT,
    cours_id INT,
    date_inscription TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (etudiant_id) REFERENCES utilisateurs(id),
    FOREIGN KEY (cours_id) REFERENCES cours(id)
);

-- Table pour les statistiques (facultative, selon votre besoin)
CREATE TABLE statistiques (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cours_id INT,
    nombre_etudiants INT,
    moyenne_notes FLOAT,
    FOREIGN KEY (cours_id) REFERENCES cours(id)
);

-- Table pour les comptes validés par l'administrateur (facultatif)
CREATE TABLE comptes_valides (
    id INT AUTO_INCREMENT PRIMARY KEY,
    utilisateur_id INT,
    date_validation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id)
);

-- Exemples de requêtes pour la gestion des données

-- Ajouter un cours
INSERT INTO cours (titre, description, enseignant_id) 
VALUES ('Introduction à la programmation', 'Apprendre les bases de la programmation', 1);

-- Inscrire un étudiant à un cours
INSERT INTO inscriptions (etudiant_id, cours_id)
VALUES (2, 1);

-- Valider un compte d'enseignant (administrateur)
INSERT INTO comptes_valides (utilisateur_id)
VALUES (3);

-- Récupérer les cours d'un étudiant
SELECT c.titre, c.description
FROM cours c
JOIN inscriptions i ON i.cours_id = c.id
WHERE i.etudiant_id = 2;

-- Récupérer les statistiques des cours
SELECT s.nombre_etudiants, s.moyenne_notes, c.titre
FROM statistiques s
JOIN cours c ON s.cours_id = c.id;
