<?php
session_start();
require_once('../controllers/DepenseController.php');
require_once('../../connexion/connexion.php');
require_once('../functions/log_depense.php');

$ancienneData = ['libelle' => '', 'montant' => '']; // valeur par défaut
$auteur = $_SESSION['user_id'] ?? 0;

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $controller = new DepenseController($connexion);

    // Récupérer l'ancienne dépense avant suppression
    $ancienneDepense = $controller->getDepenseById($id);

    if ($ancienneDepense && is_array($ancienneDepense)) {
        $ancienneData = [
            'libelle' => $ancienneDepense['motif'] ?? '',
            'montant' => $ancienneDepense['montant'] ?? ''
        ];
    }

    if ($controller->deleteDepense($id)) {
        enregistrerLogDepense($connexion, $auteur, 'suppression', $ancienneData, []);
        $_SESSION['msg'] = "Dépense supprimée avec succès.";
        $_SESSION['type'] = "success";
    } else {
        $_SESSION['msg'] = "Échec de la suppression.";
        $_SESSION['type'] = "danger";
    }
}

header('Location: ../../views/depense.php');
exit;
