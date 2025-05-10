<?php
require_once('../../connexion/connexion.php');
require_once('../controllers/UtilisateurController.php');

if (isset($_GET['id'])) {
    $controller = new UtilisateurController($connexion);
    $controller->activer(intval($_GET['id']));
    header('Location: ../../views/utilisateurs.php');
    exit;
}
else {
    $_SESSION['msg'] = "Aucun identifiant fourni.";
    $_SESSION['type'] = "danger";
    header('Location: ../../views/utilisateurs.php');
    exit;
}