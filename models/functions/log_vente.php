<?php
function enregistrer_log(PDO $connexion, string $type, array $ancien_data, array $nouveau_data, int $user_id): void
{
    try {
        $stmt = $connexion->prepare("
            INSERT INTO log_vente (type_operation, donnees_anciennes, donnees_nouvelles, id_utilisateur, supprimer)
            VALUES (:type_operation, :donnees_anciennes, :donnees_nouvelles, :id_utilisateur, 0)
        ");

        $stmt->execute([
            'type_operation'     => $type,
            'donnees_anciennes'  => json_encode($ancien_data, JSON_UNESCAPED_UNICODE),
            'donnees_nouvelles'  => json_encode($nouveau_data, JSON_UNESCAPED_UNICODE),
            'id_utilisateur'     => $user_id
        ]);
    } catch (PDOException $e) {
        error_log("Erreur d'enregistrement du log : " . $e->getMessage());
    }
}
