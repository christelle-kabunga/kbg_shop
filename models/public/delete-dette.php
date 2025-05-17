<?php
session_start();
require_once('../controllers/DetteController.php');
require_once('../../connexion/connexion.php');
require_once('../functions/log_dette.php');

$auteur = $_SESSION['user_id'] ?? 0;
$ancienneData = ['client' => '', 'montant' => ''];

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $controller = new DetteController($connexion);
    $ancienneDette = $controller->getDetteById($id);

    if ($ancienneDette) {
        $ancienneData = [
            'client' => $ancienneDette['client'],
            'montant' => $ancienneDette['montant']
        ];
    }

    if ($controller->deleteDette($id)) {
        // Journaliser la suppression
        enregistrerLogDette($connexion, $auteur, 'suppression', $ancienneData, []);
        $_SESSION['msg'] = "Dette supprimée avec succès.";
        $_SESSION['type'] = "success";
    } else {
        $_SESSION['msg'] = "Échec de la suppression.";
        $_SESSION['type'] = "danger";
    }
}

header('Location: ../../views/dette.php');
exit;
