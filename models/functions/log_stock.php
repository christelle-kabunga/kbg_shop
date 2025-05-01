<?php
function enregistrer_log_stock(PDO $connexion, string $action, array $ancien_data, array $nouveau_data, int $user_id): void
{
    try {
        $details = [];

        // Récupération du nom du produit depuis les données existantes
        $nom_produit = $nouveau_data['nom_produit'] ?? $ancien_data['nom_produit'] ?? null;

        if ($action == 'suppression') {
            $details = [
                'nom_produit'        => $nom_produit,
                'donnees_anciennes'  => $ancien_data,
                'donnees_nouvelles'  => null
            ];
        } elseif ($action == 'ajout') {
            $details = [
                'nom_produit'        => $nom_produit,
                'donnees_anciennes'  => null,
                'donnees_nouvelles'  => $nouveau_data
            ];
        } elseif ($action == 'modification') {
            $details = [
                'nom_produit'        => $nom_produit,
                'donnees_anciennes'  => $ancien_data,
                'donnees_nouvelles'  => $nouveau_data
            ];
        }

        $details_json = json_encode($details, JSON_UNESCAPED_UNICODE);

        $stmt = $connexion->prepare("
            INSERT INTO log_stock (action, details, utilisateur, date_action, supprimer)
            VALUES (:action, :details, :utilisateur, NOW(), 0)
        ");

        $stmt->execute([
            ':action'      => $action,
            ':details'     => $details_json,
            ':utilisateur' => $user_id
        ]);

    } catch (PDOException $e) {
        error_log("Erreur d'enregistrement du log stock : " . $e->getMessage());
    }
}
?>
