<?php
// Connexion à la base de données
require_once(__DIR__ . '/../../connexion/connexion.php');

// Inclusion de la classe 
require_once(__DIR__ . '/../classes/Utilisateur.php');

class UtilisateurController {
    private $connexion;

    public function __construct($connexion) {
        $this->connexion = $connexion;
    }

    public function getAllUtilisateurs(): array {
        $stmt = $this->connexion->prepare("SELECT * FROM utilisateur ORDER BY id DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function ajouter(Utilisateur $u): bool {
        $stmt = $this->connexion->prepare("INSERT INTO utilisateur (noms, email, mot_de_passe, role, actif) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([
            $u->getNoms(),
            $u->getEmail(),
            password_hash($u->getMotDePasse(), PASSWORD_DEFAULT),
            $u->getRole(),
            $u->getActif()
        ]);
    }

    public function modifier(Utilisateur $u): bool {
        $stmt = $this->connexion->prepare("UPDATE utilisateur SET noms=?, email=?, role=?, actif=? WHERE id=?");
        return $stmt->execute([
            $u->getNoms(),
            $u->getEmail(),
            $u->getRole(),
            $u->getActif(),
            $u->getId()
        ]);
    }

    public function supprimer(int $id): bool {
        // Vérifier d’abord s’il est référencé dans log_produit
        $stmt = $this->connexion->prepare("SELECT COUNT(*) FROM log_produit WHERE user_id = ?");
        $stmt->execute([$id]);
        $count = $stmt->fetchColumn();
    
        if ($count > 0) {
            // Il est utilisé, on ne peut pas supprimer
            $_SESSION['error'] = "Impossible de supprimer : cet utilisateur a des actions enregistrées.";
            return false;
        }
    
        $stmt = $this->connexion->prepare("DELETE FROM utilisateur WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
    public function getById(int $id): ?array {
        $stmt = $this->connexion->prepare("SELECT * FROM utilisateur WHERE id=?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // ✅ Nouvelle méthode pour activer un utilisateur
    public function activer(int $id): bool {
        $stmt = $this->connexion->prepare("UPDATE utilisateur SET actif = 1 WHERE id = ?");
        return $stmt->execute([$id]);

    }
    // pour desactiver
    public function desactiver(int $id): bool {
        $stmt = $this->connexion->prepare("UPDATE utilisateur SET actif = 0 WHERE id = ?");
        return $stmt->execute([$id]);

    }
}
