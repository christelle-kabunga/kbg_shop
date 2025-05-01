<?php
function enregistrer_log(PDO $connexion, string $action): void
{
    if (isset($_SESSION['user_id'])) {
        $stmt = $connexion->prepare(
            "INSERT INTO logs (user_id, action) VALUES (?, ?)"
        );
        $stmt->execute([$_SESSION['user_id'], $action]);
    }


}
function log_produit(PDO $connexion, int $user_id, string $action, array $ancien = null, array $nouveau = null): void {
    $stmt = $connexion->prepare("INSERT INTO log_produit (user_id, action, ancien_valeur, nouvelle_valeur) VALUES (?, ?, ?, ?)");
    $stmt->execute([
        $user_id,
        $action,
        $ancien ? json_encode($ancien) : null,
        $nouveau ? json_encode($nouveau) : null
    ]);
}
