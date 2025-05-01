<?php
require_once(__DIR__.'/../classes/Vente.php');

class VenteController {
    public function __construct(private PDO $db) {}

    /** Ajouter une vente et décrémenter le stock */
    public function ajouterVente(Vente $v): bool {
        try {
            $this->db->beginTransaction();

            // 1) insérer la vente
            $ins = $this->db->prepare(
              "INSERT INTO vente (produit_id, quantite, prix_unitaire, total, date_vente, utilisateur)
               VALUES (?,?,?,?,?,?)"
            );
            $ok = $ins->execute([
                $v->getProduitId(),
                $v->getQuantite(),
                $v->getPrixU(),
                $v->getTotal(),
                $v->getDateVente(),
                $v->getUtilisateur()
            ]);
            if (!$ok) throw new Exception($ins->errorInfo()[2]);

            // 2) décrémenter le stock
            $dec = $this->db->prepare(
                "UPDATE produit SET quantite = quantite - ? WHERE id = ?"
            );
            $dec->execute([$v->getQuantite(), $v->getProduitId()]);

            $this->db->commit();
            return true;

        } catch (Exception $e) {
            $this->db->rollBack();
            $_SESSION['msg']  = "Erreur vente : ".$e->getMessage();
            $_SESSION['type'] = "danger";
            return false;
        }
    }

    /** Soft‑delete */
    public function supprimerVente(int $id): bool {
        $up = $this->db->prepare("UPDATE vente SET supprimer = 0 WHERE id = ?");
        return $up->execute([$id]);
    }

    /** Liste des ventes visibles */
    public function getVentes(): array {
        $sql = "SELECT v.*, p.nom AS produit
                FROM vente v
                JOIN produit p ON v.produit_id = p.id
                WHERE v.supprimer = 1
                ORDER BY v.date_vente DESC";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
}
