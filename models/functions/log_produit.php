<?php
// fichier : models/functions/logger_produit.php

function enregistrer_log_produit(PDO $connexion, string $action, ?string $ancien_valeur, ?string $nouvelle_valeur): void
{
    $user_id = $_SESSION['user_id'] ?? 0;  // ID utilisateur connecté

    $sql = "INSERT INTO log_produit (user_id, action, ancien_valeur, nouvelle_valeur)
            VALUES (:user_id, :action, :ancien_valeur, :nouvelle_valeur)";
    $stmt = $connexion->prepare($sql);
    $stmt->execute([
        ':user_id' => $user_id,
        ':action' => $action,
        ':ancien_valeur' => $ancien_valeur,
        ':nouvelle_valeur' => $nouvelle_valeur
    ]);
}
?>



