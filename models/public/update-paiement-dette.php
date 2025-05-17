<?php
session_start();
require_once('../../connexion/connexion.php');
require_once('../functions/log_paiement_dette.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $nouveau_montant = floatval($_POST['montant_paye']);
    $nouvelle_date = date('Y-m-d H:i:s');
    $auteur = $_SESSION['user_id'] ?? 0;

    $stmt = $connexion->prepare("SELECT * FROM paiement_dette WHERE id = ?");
    $stmt->execute([$id]);
    $ancienPaiement = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($ancienPaiement) {
        $ancienne_valeur = floatval($ancienPaiement['montant_paye']);
        $diff = $nouveau_montant - $ancienne_valeur;

        $update = $connexion->prepare("UPDATE paiement_dette SET montant_paye = ?, date_paiement = ? WHERE id = ?");
        if ($update->execute([$nouveau_montant, $nouvelle_date, $id])) {
            $updateDette = $connexion->prepare("UPDATE dette SET montant_restant = montant_restant - ? WHERE id = ?");
            $updateDette->execute([$diff, $ancienPaiement['dette_id']]);

            $row = $connexion->prepare("SELECT client, montant FROM dette WHERE id = ?");
            $row->execute([$ancienPaiement['dette_id']]);
            $info = $row->fetch(PDO::FETCH_ASSOC);

            $client = $info['client'] ?? 'Inconnu';
            $montant_initial = $info['montant'] ?? 0;

            enregistrerLogPaiementDette($connexion, $auteur, 'modification', [
                'id' => $id,
                'montant' => $ancienne_valeur,
                'client' => $client,
                'montant_initial' => $montant_initial
            ], [
                'montant' => $nouveau_montant,
                'client' => $client,
                'montant_initial' => $montant_initial
            ]);

            $_SESSION['msg'] = "Paiement mis à jour avec succès.";
            $_SESSION['type'] = "success";
        } else {
            $_SESSION['msg'] = "Erreur lors de la mise à jour du paiement.";
            $_SESSION['type'] = "danger";
        }
    } else {
        $_SESSION['msg'] = "Paiement introuvable.";
        $_SESSION['type'] = "warning";
    }
}

header("Location: ../../views/paiement_dette.php");
exit;
