<?php

class DetteController {
    private PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    public function getDettes(): array {
        $stmt = $this->db->query("SELECT * FROM dette ORDER BY date_dette DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getDetteById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM dette WHERE id = ?");
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    public function addDette(Dette $dette): bool {
        $stmt = $this->db->prepare("INSERT INTO dette (client, montant, montant_restant, date_dette,utilisateur_id) VALUES (?, ?, ?, ?,?)");
        return $stmt->execute([
            $dette->getClient(),
            $dette->getMontant(),
            $dette->montant_restant,
            $dette->getDateDette(),
            $dette->getUtilisateurId(),
        ]);
    }
    
    public function getPaiementsByDetteId(int $detteId): array {
        $sql = "SELECT * FROM paiement_dette WHERE dette_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$detteId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function updateDette(int $id, Dette $dette): bool {
        $stmt = $this->db->prepare("UPDATE dette SET client = ?, montant = ?, date_dette = ?, utilisateur_id = ? WHERE id = ?");
        return $stmt->execute([
            $dette->getClient(),
            $dette->getMontant(),
            $dette->getDateDette(),
            $dette->getUtilisateurId(),
            $id
        ]);

    }
    
    public function deleteDette(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM dette WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
}
