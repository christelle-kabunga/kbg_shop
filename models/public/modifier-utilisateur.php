<?php
session_start();
require_once('../../connexion/connexion.php');
require_once('../classes/Utilisateur.php');
require_once('../controllers/UtilisateurController.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer le statut (actif ou non)
    $actif = isset($_POST['actif']) && $_POST['actif'] == '1' ? 1 : 0;

    $utilisateur = new Utilisateur(
        intval($_POST['id']),
        $_POST['noms'],
        $_POST['email'],
        '', // mot de passe non modifié
        $_POST['role'],
        $actif
    );

    $controller = new UtilisateurController($connexion);
    if ($controller->modifier($utilisateur)) {
        $_SESSION['msg'] = "Utilisateur modifié avec succès.";
    } else {
        $_SESSION['type'] = "Échec de la modification.";
    }

    header('Location: ../../views/utilisateurs.php');
    exit;
}
