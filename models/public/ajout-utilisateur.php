<?php
session_start();
require_once('../../connexion/connexion.php');
require_once('../classes/Utilisateur.php');
require_once('../controllers/UtilisateurController.php');
require_once('../functions/logger.php');  // Pour la journalisation

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $noms = $_POST['noms'] ?? '';
    $email = $_POST['email'] ?? '';
    $motDePasse = $_POST['mot_de_passe'] ?? '';
    $role = $_POST['role'] ?? 'vendeur';
    $actif = isset($_POST['actif']) ? intval($_POST['actif']) : 0;

    if ($noms && $email && $motDePasse) {
        $utilisateur = new Utilisateur(0, $noms, $email, $motDePasse, $role, $actif);
        $controller = new UtilisateurController($connexion);
        $controller->ajouter($utilisateur);

        $_SESSION['msg'] = "Utilisateur ajouté avec succès.";
        $_SESSION['type'] = "success";

    } else {
        $_SESSION['msg'] = "Erreur lors de l'ajout du produit.";
        $_SESSION['type'] = "danger";

       } 
       header('Location: ../../views/utilisateurs.php');
        exit;
}
else {
    $_SESSION['msg'] = "Méthode de requête non autorisée.";
    $_SESSION['type'] = "danger";
    header('Location: ../../views/utilisateurs.php');
    exit;
}