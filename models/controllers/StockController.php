<?php
// Connexion à la base de données
require_once(__DIR__ . '/../../connexion/connexion.php');

// Inclusion de la classe Stock
require_once(__DIR__ . '/../classes/stock.php');

class StockController {
    private $db;

    public function __construct($connexion) {
        $this->db = $connexion;
    }

    // Ajouter un mouvement de stock
    public function ajouterMouvement(Stock $stock) {
        try {
            $this->db->beginTransaction();

            $req = $this->db->prepare("INSERT INTO stock (produit_id, mouvement, quantite, date_mouvement, utilisateur) VALUES (?, ?, ?, ?, ?)");
            $req->execute([
                $stock->getProduitId(),
                $stock->getTypeMouvement(),
                $stock->getQuantite(),
                $stock->getDateMouvement(),
                $stock->getUtilisateur()
            ]);

            // Mise à jour de la quantité du produit
            $updateQuery = $stock->getTypeMouvement() === 'entrée'
                ? "UPDATE produit SET quantite = quantite + ? WHERE id = ?"
                : "UPDATE produit SET quantite = quantite - ? WHERE id = ?";
            $update = $this->db->prepare($updateQuery);
            $update->execute([
                $stock->getQuantite(),
                $stock->getProduitId()
            ]);

            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            $this->db->rollBack();
            echo "Erreur SQL : " . $e->getMessage();
            return false;
        }
    }

    // Récupérer tous les mouvements de stock
    public function getMouvements() {
        $req = $this->db->query("SELECT s.*, p.nom AS nom_produit 
                                 FROM stock s 
                                 LEFT JOIN produit p ON s.produit_id = p.id 
                                 ORDER BY s.date_mouvement DESC");
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }

    // Supprimer un mouvement
    public function supprimerMouvement($id) {
        $req = $this->db->prepare("DELETE FROM stock WHERE id = ?");
        return $req->execute([$id]);
    }

    // Modifier un mouvement
    public function modifierMouvement(Stock $stock) {
        // ⚠️ Attention : ici, tu dois aussi mettre à jour la quantité du produit
        // selon la différence entre l'ancienne et la nouvelle quantité + type
        // Cela nécessite d’abord récupérer l'ancien mouvement pour ajuster

        try {
            $this->db->beginTransaction();

            // Récupérer ancien mouvement
            $ancien = $this->getMouvementById($stock->getId());

            // Annuler l'effet de l'ancien mouvement
            $queryReset = $ancien['mouvement'] === 'entrée'
                ? "UPDATE produit SET quantite = quantite - ? WHERE id = ?"
                : "UPDATE produit SET quantite = quantite + ? WHERE id = ?";
            $this->db->prepare($queryReset)->execute([
                $ancien['quantite'],
                $ancien['produit_id']
            ]);

            // Mettre à jour le mouvement
            $req = $this->db->prepare("UPDATE stock SET produit_id = ?, mouvement = ?, quantite = ?, date_mouvement = ?, utilisateur = ? WHERE id = ?");
            $req->execute([
                $stock->getProduitId(),
                $stock->getTypeMouvement(),
                $stock->getQuantite(),
                $stock->getDateMouvement(),
                $stock->getUtilisateur(),
                $stock->getId()
            ]);

            // Appliquer le nouvel effet
            $queryApply = $stock->getTypeMouvement() === 'entrée'
                ? "UPDATE produit SET quantite = quantite + ? WHERE id = ?"
                : "UPDATE produit SET quantite = quantite - ? WHERE id = ?";
            $this->db->prepare($queryApply)->execute([
                $stock->getQuantite(),
                $stock->getProduitId()
            ]);

            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            $this->db->rollBack();
            echo "Erreur SQL : " . $e->getMessage();
            return false;
        }
    }

    // Récupérer un mouvement par son ID
    public function getMouvementById($id) {
        $req = $this->db->prepare("SELECT * FROM stock WHERE id = ?");
        $req->execute([$id]);
        return $req->fetch(PDO::FETCH_ASSOC);
    }
}
?>
