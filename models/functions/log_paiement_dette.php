<?php
function enregistrerLogPaiementDette($connexion, int $utilisateur_id, string $action, array $ancien = [], array $nouveau = []): void {
    $details = "";
    $client = 'Inconnu';
    $montant_dette = 'Inconnu';

    if ($action === 'ajout') {
        $dette_id = $nouveau['dette_id'] ?? null;
        $montant = $nouveau['montant'] ?? 0;

        if ($dette_id) {
            $stmt = $connexion->prepare("SELECT client, montant FROM dette WHERE id = ?");
            $stmt->execute([$dette_id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $client = $row['client'] ?? $client;
            $montant_dette = $row['montant'] ?? $montant_dette;
        }

        $details = "Paiement ajouté → Client: $client ; Montant payé: $montant ; Dette initiale: $montant_dette";

    } elseif ($action === 'modification') {
        $paiement_id = $ancien['id'] ?? null;
        $ancien_montant = $ancien['montant'] ?? 0;
        $nouveau_montant = $nouveau['montant'] ?? 0;

        if ($paiement_id) {
            $stmt = $connexion->prepare("
                SELECT d.client, d.montant 
                FROM paiement_dette p 
                JOIN dette d ON p.dette_id = d.id 
                WHERE p.id = ?
            ");
            $stmt->execute([$paiement_id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $client = $row['client'] ?? $client;
            $montant_dette = $row['montant'] ?? $montant_dette;
        }

        $details = "Paiement modifié → Client: $client ; Ancien montant: $ancien_montant ; Nouveau montant: $nouveau_montant ; Dette initiale: $montant_dette";

    } elseif ($action === 'suppression') {
        // 🔥 On n’interroge plus la base, on utilise ce qu’on a déjà récupéré avant la suppression
        $montant = $ancien['montant'] ?? 0;
        $client = $ancien['client'] ?? $client;
        $montant_dette = $ancien['montant_initial'] ?? $montant_dette;

        $details = "Paiement supprimé → Client: $client ; Montant payé: $montant ; Dette initiale: $montant_dette";
    }

    $stmt = $connexion->prepare("
        INSERT INTO log_paiement_dette (utilisateur_id, action, details, date_action) 
        VALUES (?, ?, ?, NOW())
    ");
    $stmt->execute([$utilisateur_id, $action, $details]);
}

