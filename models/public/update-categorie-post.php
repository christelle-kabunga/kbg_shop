<?php
require_once('../../connexion/connexion.php');
require_once('../classes/Categorie.php');
require_once('../controllers/CategorieController.php');

if (isset($_POST['id'], $_POST['libelle'])) {
    $id = intval($_POST['id']);
    $libelle = htmlspecialchars(trim($_POST['libelle']));

    $categorie = new Categorie($libelle, $id);
    $controller = new CategorieController($connexion);
       // Passer l'objet Categorie à la méthode modifierCategorie
       $result = $controller->modifierCategorie($categorie);

    $_SESSION['msg'] = $result ? "Catégorie modifiée avec succès." : "Erreur lors de la modification.";
    $_SESSION['type'] = $result ? "success" : "danger";
   
}
header('Location: ../../views/categories.php');
exit;
?>
