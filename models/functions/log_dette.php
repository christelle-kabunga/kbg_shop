<?php
function enregistrerLogDette(PDO $db, int $utilisateur_id, string $action, array $ancienne, array $nouvelle): void {
    $details = '';

    if ($action === 'ajout') {
        $details = "Client: {$nouvelle['client']} ; Montant: {$nouvelle['montant']}";
    } elseif ($action === 'modification') {
        $details = "Avant -> Client: {$ancienne['client']}, Montant: {$ancienne['montant']} | ";
        $details .= "Après -> Client: {$nouvelle['client']}, Montant: {$nouvelle['montant']}";
    } elseif ($action === 'suppression') {
        $details = "Client: {$ancienne['client']} ; Montant: {$ancienne['montant']}";
    }

    $stmt = $db->prepare("
        INSERT INTO log_dette (utilisateur, action, details, date_log)
        VALUES (?, ?, ?, NOW())
    ");
    $stmt->execute([$utilisateur_id, $action, $details]);
}
