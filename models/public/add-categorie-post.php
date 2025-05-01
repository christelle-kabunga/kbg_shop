<?php
require_once('../../connexion/connexion.php');
require_once('../classes/Categorie.php');
require_once('../controllers/CategorieController.php');
require_once('../functions/logger.php');  

session_start();

if (isset($_POST['nom'])) {
    $categorie = new Categorie($_POST['nom']);
    $controller = new CategorieController($connexion);
    $result = $controller->ajouterCategorie($categorie);
     // ---- Journalisation de l’action ----
     $libelle = ucfirst($mouvement) . " ajout cat : car=$nom";
     enregistrer_log($connexion, $libelle, $utilisateur);   // << écrit dans logs

    $_SESSION['msg'] = $result ? "Catégorie ajoutée avec succès." : "Erreur lors de l’ajout.";
    $_SESSION['type'] = $result ? "success" : "danger";
}

header('Location: ../../views/categories.php');
exit;
