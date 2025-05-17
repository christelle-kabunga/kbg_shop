<?php
session_start();
require_once('../classes/Dette.php');
require_once('../controllers/DetteController.php');
require_once('../../connexion/connexion.php');
require_once('../functions/log_dette.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $client = trim($_POST['client']);
    $montant = floatval($_POST['montant']);
    $date = date('Y-m-d H:i:s');
    $auteur = $_SESSION['user_id'] ?? 0;

    $controller = new DetteController($connexion);
    $ancienneDette = $controller->getDetteById($id);

    // Vérification ajoutée
    $paiements = $controller->getPaiementsByDetteId($id); // méthode à créer dans le controller
    $montantInitial = floatval($ancienneDette['montant'] ?? 0);
    $modificationMontant = $montant != $montantInitial;

    if ($modificationMontant && count($paiements) > 0) {
        $_SESSION['msg'] = "Impossible de modifier le montant : des paiements ont déjà été effectués.";
        $_SESSION['type'] = "danger";
        header('Location: ../../views/dette.php');
        exit;
    }

    $ancienne = [
        'client' => $ancienneDette['client'] ?? '',
        'montant' => $ancienneDette['montant'] ?? ''
    ];

    $dette = new Dette(null, $client, $montant, $date, $auteur, $montant);

    if ($controller->updateDette($id, $dette)) {
        $nouvelle = ['client' => $client, 'montant' => $montant];
        enregistrerLogDette($connexion, $auteur, 'modification', $ancienne, $nouvelle);
        $_SESSION['msg'] = "Dette modifiée avec succès.";
        $_SESSION['type'] = "success";
    } else {
        $_SESSION['msg'] = "Erreur lors de la modification.";
        $_SESSION['type'] = "danger";
    }

    header('Location: ../../views/dette.php');
    exit;
}
