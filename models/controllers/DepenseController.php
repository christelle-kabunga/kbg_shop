<?php
class DepenseController {
    private PDO $db;

    public function __construct(PDO $connexion) {
        $this->db = $connexion;
    }

    public function getDepenses(): array {
        $stmt = $this->db->query("
        SELECT d.id AS depense_id, d.motif, d.montant, d.date_depense, d.auteur, u.noms
        FROM depenses d
        INNER JOIN utilisateur u ON d.auteur = u.id
        ORDER BY d.date_depense DESC
    ");    
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getDepenseById(int $id): ?array {
        $stmt = $this->db->prepare("
            SELECT d.id AS depense_id, d.motif, d.montant, d.date_depense, d.auteur, u.noms
            FROM depenses d
            INNER JOIN utilisateur u ON d.auteur = u.id
            WHERE d.id = ?
        ");
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }
    
    public function addDepense(Depense $depense): bool {
        $stmt = $this->db->prepare("INSERT INTO depenses (motif, montant, date_depense, auteur) VALUES (?, ?, ?, ?)");
        return $stmt->execute([
            $depense->getLibelle(),
            $depense->getMontant(),
            $depense->getDate(),
            $depense->getAuteur()
        ]);
    }

    public function updateDepense(int $id, Depense $depense): bool {
        $stmt = $this->db->prepare("UPDATE depenses SET motif = ?, montant = ?, date_depense = ?, auteur = ? WHERE id = ?");
        return $stmt->execute([
            $depense->getLibelle(),
            $depense->getMontant(),
            $depense->getDate(),
            $depense->getAuteur(),
            $id
        ]);
    }

    public function deleteDepense(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM depenses WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
