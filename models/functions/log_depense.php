<?php
function enregistrerLogDepense(PDO $db, int $utilisateur_id, string $action, array $ancienne, array $nouvelle): void {
    $details = '';

    if ($action === 'ajout') {
        $details = "Libellé: {$nouvelle['libelle']} ; Montant: {$nouvelle['montant']}";
    } elseif ($action === 'modification') {
        $details = "Avant -> Libellé: {$ancienne['libelle']}, Montant: {$ancienne['montant']} | ";
        $details .= "Après -> Libellé: {$nouvelle['libelle']}, Montant: {$nouvelle['montant']}";
    } elseif ($action === 'suppression') {
        $details = "Libellé: {$ancienne['libelle']} ; Montant: {$ancienne['montant']}";
    }

    $stmt = $db->prepare("
        INSERT INTO log_depenses (utilisateur_id, action, details, date_action, supprimer)
        VALUES (?, ?, ?, NOW(), 1)
    ");
    $stmt->execute([$utilisateur_id, $action, $details]);
}

