<?php
// Connexion PDO
require_once(__DIR__ . '/../../connexion/connexion.php');
// Classe Produit
require_once(__DIR__ . '/../classes/produit.php');

class ProduitController {
    private PDO $db;

    public function __construct(PDO $connexion) {
        // s’assurer que PDO lève bien les exceptions
        $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->db = $connexion;
    }

    /** Ajouter un produit */
    public function ajouterProduit(Produit $produit): bool {
        try {
            $req = $this->db->prepare(
                "INSERT INTO produit (nom, id_categorie, seuil, prix, quantite)
                 VALUES (?, ?, ?, ?, ?)"
            );
            return $req->execute([
                $produit->getNom(),
                $produit->getCategorie(),   // FK catégorie
                $produit->getSeuil(),
                $produit->getPrix(),
                $produit->getQuantite()
            ]);

        } catch (PDOException $e) {
            // message d’erreur lisible pour la vue
            $_SESSION['msg']  = "Erreur SQL : " . $e->getMessage();
            $_SESSION['type'] = "danger";
            return false;
        }
    }

    /** Récupérer tous les produits */
    public function getProduits(): array {
        $sql = "SELECT p.*, c.nom AS categorie
                FROM produit p
                LEFT JOIN categorie c ON p.id_categorie = c.id
                ORDER BY p.id DESC";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function supprimerProduit(int $id): bool
    {
        // Vérifier s’il existe au moins un mouvement de stock
        $chk = $this->db->prepare("SELECT COUNT(*) FROM stock WHERE produit_id = ?");
        $chk->execute([$id]);
        $liens = $chk->fetchColumn();
    
        if ($liens > 0) {
            $_SESSION['msg']  = "Impossible : ce produit est lié à $liens mouvement(s) de stock.";
            $_SESSION['type'] = "warning";
            return false;
        }
    
        // Aucun lien → on peut supprimer
        try {
            $stmt = $this->db->prepare("DELETE FROM produit WHERE id = :id"); // Utilisation de $this->db
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute(); // Retourne true si la suppression réussit
        } catch (Exception $e) {
            return false; // Retourne false en cas d'erreur
        }
    }
    

    /** Modifier un produit */
    public function modifierProduit(Produit $produit): bool {
        $req = $this->db->prepare(
            "UPDATE produit
             SET nom = ?, id_categorie = ?, seuil = ?, prix = ?, quantite = ?
             WHERE id = ?"
        );
        return $req->execute([
            $produit->getNom(),
            $produit->getCategorie(),
            $produit->getSeuil(),
            $produit->getPrix(),
            $produit->getQuantite(),
            $produit->getId()
        ]);
    }

    /** Récupérer un produit par son ID */
    public function getProduitById(int $id): ?array {
        $req = $this->db->prepare("SELECT * FROM produit WHERE id = ?");
        $req->execute([$id]);
        return $req->fetch(PDO::FETCH_ASSOC) ?: null;
    }
}
?>
