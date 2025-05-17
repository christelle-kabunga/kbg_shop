<?php

class PaiementController {
    private PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    public function getPaiements(): array {
        $sql = "
            SELECT 
                p.id,
                d.client,
                p.montant_paye,
                p.date_paiement,
                u.noms AS utilisateur,
                d.montant AS montant_dette,
                (
                    d.montant - COALESCE((
                        SELECT SUM(p2.montant_paye) 
                        FROM paiement_dette p2 
                        WHERE p2.dette_id = d.id
                    ), 0)
                ) AS montant_restant
            FROM paiement_dette p
            JOIN dette d ON p.dette_id = d.id
            JOIN utilisateur u ON p.utilisateur_id = u.id
            ORDER BY p.date_paiement DESC
        ";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function addPaiement(PaiementDette $paiement): bool {
        $stmt = $this->db->prepare("
            INSERT INTO paiement_dette (dette_id, montant_paye, date_paiement, utilisateur_id)
            VALUES (?, ?, ?, ?)
        ");
        return $stmt->execute([
            $paiement->getDetteId(),
            $paiement->getMontantPaye(),
            $paiement->getDatePaiement(),
            $paiement->getUtilisateurId()
        ]);
    }

    public function getPaiementByDette(int $detteId): array {
        $stmt = $this->db->prepare("
            SELECT * FROM paiement_dette 
            WHERE dette_id = ? 
            ORDER BY date_paiement DESC
        ");
        $stmt->execute([$detteId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateMontantRestant(int $dette_id): void {
        // Total payé
        $stmt = $this->db->prepare("SELECT SUM(montant_paye) AS total FROM paiement_dette WHERE dette_id = ?");
        $stmt->execute([$dette_id]);
        $total_paye = $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;
        $total_paye = (float) $total_paye;

        // Montant de la dette
        $stmt = $this->db->prepare("SELECT montant FROM dette WHERE id = ?");
        $stmt->execute([$dette_id]);
        $montant_dette = $stmt->fetch(PDO::FETCH_ASSOC)['montant'] ?? 0;
        $montant_dette = (float) $montant_dette;

        // Calcul du reste à payer
        $reste = $montant_dette - $total_paye;
        $reste = $reste < 0 ? 0 : $reste;

        // Mise à jour du champ montant_restant
        $update = $this->db->prepare("UPDATE dette SET montant_restant = ? WHERE id = ?");
        $update->execute([$reste, $dette_id]);
    }

    public function deletePaiement(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM paiement_dette WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
    
