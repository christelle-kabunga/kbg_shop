<?php
require_once('../../connexion/connexion.php');
require_once('../controllers/CategorieController.php');
require_once('../functions/logger.php');   

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $controller = new CategorieController($connexion);

    // Capture le résultat de la suppression
    $result = $controller->supprimerCategorie($id);
     // ---- Journalisation de l’action ----
     $libelle = ucfirst($mouvement) . " suppression: categorie = $nom";
     enregistrer_log($connexion, $libelle, $utilisateur);   // << écrit dans logs
 

    session_start();
    $_SESSION['msg'] = $result ? "Catégorie supprimée avec succès." : "Erreur lors de la suppression.";
    $_SESSION['type'] = $result ? "success" : "danger";
}

header('Location: ../../views/categories.php');
exit;