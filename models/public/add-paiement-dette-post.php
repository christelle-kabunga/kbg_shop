<?php
if (session_status() === PHP_SESSION_NONE) session_start();

require_once('../../connexion/connexion.php');
require_once('../functions/log_paiement_dette.php');

$dette_id = $_POST['dette_id'] ?? null;
$montant_paye = floatval($_POST['montant_paye']);
$date_paiement = date('Y-m-d H:i:s');
$utilisateur_id = $_SESSION['user_id'] ?? 0;

if (!$utilisateur_id) {
    $_SESSION['msg'] = "Utilisateur non connecté.";
    $_SESSION['type'] = "danger";
    header('Location: ../../views/paiement_dette.php');
    exit;
}

$stmt_verif = $connexion->prepare("SELECT montant_restant FROM dette WHERE id = ?");
$stmt_verif->execute([$dette_id]);
$dette = $stmt_verif->fetch(PDO::FETCH_ASSOC);

if (!$dette) {
    $_SESSION['msg'] = "Dette introuvable.";
    $_SESSION['type'] = "danger";
    header('Location: ../../views/paiement_dette.php');
    exit;
}

$montant_restant = floatval($dette['montant_restant']);

if ($montant_paye > $montant_restant) {
    $_SESSION['msg'] = "Le montant payé dépasse le montant restant de la dette ({$montant_restant}).";
    $_SESSION['type'] = "danger";
    header('Location: ../../views/paiement_dette.php');
    exit;
}

try {
    $stmt = $connexion->prepare("INSERT INTO paiement_dette (dette_id, montant_paye, date_paiement, utilisateur_id) VALUES (?, ?, ?, ?)");
    $stmt->execute([$dette_id, $montant_paye, $date_paiement, $utilisateur_id]);

    $update = $connexion->prepare("UPDATE dette SET montant_restant = montant_restant - ? WHERE id = ?");
    $update->execute([$montant_paye, $dette_id]);

    // Journalisation
    enregistrerLogPaiementDette($connexion, $utilisateur_id, 'ajout', [], [
        'dette_id' => $dette_id,
        'montant' => $montant_paye
    ]);

    $_SESSION['msg'] = "Paiement enregistré avec succès.";
    $_SESSION['type'] = "success";
} catch (PDOException $e) {
    $_SESSION['msg'] = "Erreur : " . $e->getMessage();
    $_SESSION['type'] = "danger";
}

header('Location: ../../views/paiement_dette.php');
exit;
