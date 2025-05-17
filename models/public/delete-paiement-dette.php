<?php
session_start();
require_once '../../connexion/connexion.php';
require_once '../functions/log_paiement_dette.php';

if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];
    $utilisateur_id = $_SESSION['user_id'] ?? 0;

    $stmt = $connexion->prepare("
        SELECT pd.*, d.client, d.montant AS montant_initial
        FROM paiement_dette pd
        JOIN dette d ON pd.dette_id = d.id
        WHERE pd.id = ?
    ");
    $stmt->execute([$id]);
    $paiement = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($paiement) {
        $dette_id = $paiement['dette_id'];
        $montant_paye = floatval($paiement['montant_paye']);
        $client = $paiement['client'];
        $montant_initial = $paiement['montant_initial'];

        // Supprimer le paiement
        $delete = $connexion->prepare("DELETE FROM paiement_dette WHERE id = ?");
        if ($delete->execute([$id])) {
            // Recréditer la dette
            $update = $connexion->prepare("UPDATE dette SET montant_restant = montant_restant + ? WHERE id = ?");
            $update->execute([$montant_paye, $dette_id]);

            // Journalisation avec données correctes
            enregistrerLogPaiementDette($connexion, $utilisateur_id, 'suppression', [
                'id' => $id,
                'montant' => $montant_paye,
                'client' => $client,
                'montant_initial' => $montant_initial
            ]);

            $_SESSION['msg'] = "Paiement supprimé avec succès.";
            $_SESSION['type'] = "success";
        } else {
            $_SESSION['msg'] = "Erreur lors de la suppression.";
            $_SESSION['type'] = "danger";
        }
    } else {
        $_SESSION['msg'] = "Paiement introuvable.";
        $_SESSION['type'] = "warning";
    }

    header("Location: ../../views/paiement_dette.php");
    exit;
}
