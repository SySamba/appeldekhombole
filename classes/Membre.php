<?php
require_once __DIR__ . '/../config/database.php';

class Membre {
    private $conn;
    private $table = 'membres';

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Ajouter un membre
    public function ajouter($data) {
        $query = "INSERT INTO " . $this->table . " 
                  (nom, prenom, date_naissance, telephone, email, adresse, photo, role, statut, date_adhesion, qr_code) 
                  VALUES (:nom, :prenom, :date_naissance, :telephone, :email, :adresse, :photo, :role, :statut, :date_adhesion, :qr_code)";
        
        $stmt = $this->conn->prepare($query);
        
        // Génération du QR code unique
        $qr_code = $this->genererQRCode($data['nom'], $data['prenom'], $data['telephone']);
        
        $stmt->bindParam(':nom', $data['nom']);
        $stmt->bindParam(':prenom', $data['prenom']);
        $stmt->bindParam(':date_naissance', $data['date_naissance']);
        $stmt->bindParam(':telephone', $data['telephone']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':adresse', $data['adresse']);
        $stmt->bindParam(':photo', $data['photo']);
        $stmt->bindParam(':role', $data['role']);
        $stmt->bindParam(':statut', $data['statut']);
        $stmt->bindParam(':date_adhesion', $data['date_adhesion']);
        $stmt->bindParam(':qr_code', $qr_code);
        
        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        return false;
    }

    // Modifier un membre
    public function modifier($id, $data) {
        $query = "UPDATE " . $this->table . " 
                  SET nom = :nom, prenom = :prenom, date_naissance = :date_naissance, 
                      telephone = :telephone, email = :email, adresse = :adresse, 
                      photo = :photo, role = :role, statut = :statut 
                  WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':nom', $data['nom']);
        $stmt->bindParam(':prenom', $data['prenom']);
        $stmt->bindParam(':date_naissance', $data['date_naissance']);
        $stmt->bindParam(':telephone', $data['telephone']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':adresse', $data['adresse']);
        $stmt->bindParam(':photo', $data['photo']);
        $stmt->bindParam(':role', $data['role']);
        $stmt->bindParam(':statut', $data['statut']);
        
        return $stmt->execute();
    }

    // Supprimer un membre
    public function supprimer($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // Obtenir tous les membres
    public function obtenirTous($limit = null, $offset = null) {
        $query = "SELECT * FROM " . $this->table . " ORDER BY nom, prenom";
        
        if ($limit) {
            $query .= " LIMIT :limit";
            if ($offset) {
                $query .= " OFFSET :offset";
            }
        }
        
        $stmt = $this->conn->prepare($query);
        
        if ($limit) {
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            if ($offset) {
                $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            }
        }
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtenir un membre par ID
    public function obtenirParId($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Obtenir un membre par QR code
    public function obtenirParQRCode($qr_code) {
        $query = "SELECT * FROM " . $this->table . " WHERE qr_code = :qr_code";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':qr_code', $qr_code);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Obtenir un membre par données QR (pour les nouveaux QR codes avec toutes les infos)
    public function obtenirParDonneesQR($qr_data) {
        // Extraire le nom et prénom du QR code
        if (preg_match('/MEMBRE:\s*(.+?)\s*\n/', $qr_data, $matches)) {
            $nom_complet = trim($matches[1]);
            $parts = explode(' ', $nom_complet, 2);
            if (count($parts) >= 2) {
                $prenom = $parts[0];
                $nom = $parts[1];
                
                $query = "SELECT * FROM " . $this->table . " WHERE prenom = :prenom AND nom = :nom";
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':prenom', $prenom);
                $stmt->bindParam(':nom', $nom);
                $stmt->execute();
                return $stmt->fetch(PDO::FETCH_ASSOC);
            }
        }
        
        // Si pas trouvé par nom, essayer par téléphone
        if (preg_match('/TEL:\s*(.+?)\s*\n/', $qr_data, $matches)) {
            $telephone = trim($matches[1]);
            $query = "SELECT * FROM " . $this->table . " WHERE telephone = :telephone";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':telephone', $telephone);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
        
        return false;
    }

    // Rechercher des membres
    public function rechercher($terme) {
        $query = "SELECT * FROM " . $this->table . " 
                  WHERE nom LIKE :terme OR prenom LIKE :terme OR telephone LIKE :terme OR email LIKE :terme 
                  ORDER BY nom, prenom";
        
        $stmt = $this->conn->prepare($query);
        $terme = "%{$terme}%";
        $stmt->bindParam(':terme', $terme);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Compter le nombre total de membres
    public function compterTotal() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }

    // Générer un code QR unique
    private function genererQRCode($nom, $prenom, $telephone) {
        return 'QR_' . strtoupper(substr($nom, 0, 3)) . strtoupper(substr($prenom, 0, 3)) . '_' . 
               substr($telephone, -4) . '_' . time();
    }

    // Obtenir les statistiques
    public function obtenirStatistiques() {
        $stats = [];
        
        // Total des membres
        $query = "SELECT COUNT(*) as total FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $stats['total'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        // Membres actifs
        $query = "SELECT COUNT(*) as actifs FROM " . $this->table . " WHERE statut = 'Actif'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $stats['actifs'] = $stmt->fetch(PDO::FETCH_ASSOC)['actifs'];
        
        // Répartition par rôle
        $query = "SELECT role, COUNT(*) as nombre FROM " . $this->table . " GROUP BY role";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $stats['roles'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return $stats;
    }

    // Upload de photo
    public function uploadPhoto($file) {
        // Utiliser un chemin relatif depuis la racine du projet
        $target_dir = "uploads/photos/";
        
        // Créer le dossier s'il n'existe pas
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        
        $file_extension = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
        $new_filename = uniqid() . '.' . $file_extension;
        $target_file = $target_dir . $new_filename;
        
        // Vérifier si c'est une vraie image
        $check = getimagesize($file["tmp_name"]);
        if ($check === false) {
            return false;
        }
        
        // Vérifier la taille du fichier (max 5MB)
        if ($file["size"] > 5000000) {
            return false;
        }
        
        // Autoriser certains formats
        if (!in_array($file_extension, ["jpg", "jpeg", "png", "gif"])) {
            return false;
        }
        
        if (move_uploaded_file($file["tmp_name"], $target_file)) {
            return $new_filename;
        }
        
        return false;
    }
}
?>
