<?php
session_start();
require_once('../../connexion/connexion.php');
require_once('../controllers/UtilisateurController.php');

if (isset($_GET['id'])) {
    $controller = new UtilisateurController($connexion);
    
    try {
        $resultat = $controller->supprimer(intval($_GET['id']));

        if ($resultat) {
            $_SESSION['msg'] = "Utilisateur supprimé avec succès.";
            $_SESSION['type'] = "success";
        } else {
            $_SESSION['msg'] = "Impossible de supprimer l'utilisateur : il est lié à des données (ex. journaux d'activité).";
            $_SESSION['type'] = "warning";
        }

    } catch (PDOException $e) {
        $_SESSION['msg'] = "Erreur lors de la suppression de l'utilisateur. Détails : " . $e->getMessage();
        $_SESSION['type'] = "danger";
    }

} else {
    $_SESSION['msg'] = "Aucun identifiant d'utilisateur fourni.";
    $_SESSION['type'] = "danger";
}

header('Location: ../../views/utilisateurs.php');
exit;
