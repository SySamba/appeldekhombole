-- Création de la base de données
CREATE DATABASE IF NOT EXISTS khombole_membres CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE khombole_membres;

-- Table des membres
CREATE TABLE IF NOT EXISTS membres (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    date_naissance DATE NULL,
    telephone VARCHAR(20) NOT NULL,
    email VARCHAR(150) NULL,
    adresse TEXT NOT NULL,
    photo VARCHAR(255) NULL,
    role ENUM('Membre', 'Responsable', 'Président', 'Vice-Président', 'Secrétaire', 'Trésorier', 'Autre') DEFAULT 'Membre',
    statut ENUM('Actif', 'Inactif', 'Suspendu') DEFAULT 'Actif',
    date_adhesion DATE NOT NULL,
    qr_code VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Index pour optimiser les recherches
CREATE INDEX idx_nom_prenom ON membres(nom, prenom);
CREATE INDEX idx_telephone ON membres(telephone);
CREATE INDEX idx_email ON membres(email);
CREATE INDEX idx_statut ON membres(statut);
CREATE INDEX idx_role ON membres(role);

-- Insertion de quelques données de test
INSERT INTO membres (nom, prenom, telephone, email, adresse, role, date_adhesion) VALUES
('DIOP', 'Amadou', '77 123 45 67', 'amadou.diop@email.com', 'Khombole, Quartier Nord', 'Président', '2024-01-15'),
('FALL', 'Fatou', '76 987 65 43', 'fatou.fall@email.com', 'Khombole, Centre Ville', 'Secrétaire', '2024-02-20'),
('NDIAYE', 'Moussa', '78 456 78 90', 'moussa.ndiaye@email.com', 'Khombole, Quartier Sud', 'Membre', '2024-03-10');
