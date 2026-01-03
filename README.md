# 🎯 Système de Gestion des Membres - Association de Khombole

## 📋 Description

Système complet de gestion des membres avec génération de cartes professionnelles et QR codes. Développé avec PHP, MySQL, Bootstrap et Tailwind CSS selon les couleurs officielles de l'association.

## ✨ Fonctionnalités

### 🎫 Cartes de Membres Professionnelles
- **Design moderne** avec les couleurs du logo (vert #0b843e et jaune #f4e93d)
- **QR codes intégrés** pour chaque membre
- **Cartes recto-verso** avec toutes les informations
- **Impression optimisée** pour cartes physiques

### 👥 Gestion Complète des Membres
- ✅ **Ajouter** de nouveaux membres
- ✏️ **Modifier** les informations existantes
- 🗑️ **Supprimer** des membres
- 🔍 **Rechercher** par nom, prénom, téléphone ou email
- 📊 **Statistiques** en temps réel

### 📱 Scanner QR Codes
- **Scanner caméra** intégré (HTML5)
- **Saisie manuelle** des codes QR
- **Affichage complet** des informations du membre
- **Interface responsive** mobile et desktop

### 🎨 Interface Utilisateur
- **Design professionnel** aux couleurs de l'association
- **Responsive** (mobile, tablette, desktop)
- **Animations CSS** fluides
- **UX optimisée** avec Bootstrap 5

## 🛠️ Technologies Utilisées

- **Backend**: PHP 7.4+ avec PDO
- **Base de données**: MySQL 5.7+
- **Frontend**: HTML5, CSS3, JavaScript
- **Frameworks CSS**: Bootstrap 5.3 + Tailwind CSS 2.2
- **Icons**: Font Awesome 6.0
- **QR Codes**: API QR Server + HTML5-QRCode
- **Fonts**: Google Fonts (Poppins)

## 📦 Installation

### 1. Prérequis
- XAMPP/WAMP/LAMP avec PHP 7.4+
- MySQL 5.7+
- Navigateur moderne (Chrome, Firefox, Safari, Edge)

### 2. Configuration de la base de données

```sql
-- Exécuter le fichier sql/create_database.sql
mysql -u root -p < sql/create_database.sql
```

Ou via phpMyAdmin :
1. Créer une base de données `khombole_membres`
2. Importer le fichier `sql/create_database.sql`

### 3. Configuration PHP

Modifier `config/database.php` si nécessaire :
```php
private $host = 'localhost';
private $db_name = 'khombole_membres';
private $username = 'root';
private $password = '';
```

### 4. Permissions des dossiers

Créer et donner les permissions d'écriture :
```bash
mkdir uploads/photos
chmod 755 uploads/photos
```

## 🚀 Utilisation

### Accès au système
- **Page d'accueil**: `http://localhost/a-appel-de-khombole/`
- **Gestion des membres**: `http://localhost/a-appel-de-khombole/gestion_membres.php`
- **Scanner QR**: `http://localhost/a-appel-de-khombole/scanner_qr.php`

### Workflow complet

1. **Ajouter un membre**
   - Aller sur "Gestion des Membres"
   - Cliquer "Ajouter Membre"
   - Remplir le formulaire avec photo (optionnelle)
   - Valider → QR code généré automatiquement

2. **Générer une carte**
   - Dans la liste des membres, cliquer sur l'icône carte
   - La carte s'affiche avec QR code
   - Imprimer directement depuis le navigateur

3. **Scanner un QR code**
   - Aller sur "Scanner QR"
   - Utiliser la caméra ou saisir manuellement
   - Toutes les informations s'affichent instantanément

## 📊 Structure de la Base de Données

### Table `membres`
```sql
- id (INT, AUTO_INCREMENT, PRIMARY KEY)
- nom (VARCHAR(100), NOT NULL)
- prenom (VARCHAR(100), NOT NULL)
- date_naissance (DATE, NULL)
- telephone (VARCHAR(20), NOT NULL)
- email (VARCHAR(150), NULL)
- adresse (TEXT, NOT NULL)
- photo (VARCHAR(255), NULL)
- role (ENUM: Membre, Responsable, Président, etc.)
- statut (ENUM: Actif, Inactif, Suspendu)
- date_adhesion (DATE, NOT NULL)
- qr_code (VARCHAR(255), NULL)
- created_at (TIMESTAMP)
- updated_at (TIMESTAMP)
```

## 🎨 Personnalisation des Couleurs

Les couleurs sont basées sur le logo de l'association :
- **Vert principal**: `#0b843e`
- **Vert foncé**: `#1a5f2e`
- **Jaune**: `#f4e93d`
- **Jaune foncé**: `#e6d635`

Pour modifier les couleurs, éditer les variables CSS dans chaque fichier.

## 📱 Fonctionnalités Mobiles

- **Interface responsive** adaptée aux smartphones
- **Scanner QR natif** avec caméra mobile
- **Cartes optimisées** pour l'affichage mobile
- **Navigation tactile** intuitive

## 🔒 Sécurité

- **Requêtes préparées** PDO contre les injections SQL
- **Validation** côté client et serveur
- **Upload sécurisé** des images avec vérification
- **Gestion d'erreurs** robuste

## 📋 Fonctionnalités Avancées

### QR Codes
- **Génération unique** par membre
- **Format**: `QR_[3LETTRES_NOM][3LETTRES_PRENOM]_[4CHIFFRES_TEL]_[TIMESTAMP]`
- **API externe** pour génération d'images QR
- **Scanner HTML5** intégré

### Cartes Professionnelles
- **Design gradient** aux couleurs de l'association
- **Logo intégré** en filigrane
- **Informations complètes** recto-verso
- **Optimisation impression** A4 et format carte

### Recherche et Filtres
- **Recherche globale** sur nom, prénom, téléphone, email
- **Statistiques temps réel** (total, actifs, rôles)
- **Pagination** pour grandes listes
- **Tri automatique** alphabétique

## 🐛 Dépannage

### Problèmes courants

1. **Erreur de connexion base de données**
   - Vérifier les paramètres dans `config/database.php`
   - S'assurer que MySQL est démarré

2. **Photos ne s'affichent pas**
   - Vérifier les permissions du dossier `uploads/photos/`
   - Contrôler la taille des fichiers (max 5MB)

3. **QR codes ne se génèrent pas**
   - Vérifier la connexion internet (API externe)
   - Contrôler les caractères spéciaux dans les données

4. **Scanner caméra ne fonctionne pas**
   - Utiliser HTTPS ou localhost
   - Autoriser l'accès caméra dans le navigateur

## 📞 Support

Pour toute question ou problème :
- Vérifier ce README
- Consulter les commentaires dans le code
- Tester avec les données d'exemple fournies

## 📄 Licence

Système développé pour l'Association de Khombole.
Tous droits réservés © 2024

---

**🎯 Système prêt à l'emploi avec interface professionnelle et fonctionnalités complètes !**
