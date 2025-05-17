<?php
session_start();
require_once('../classes/Dette.php');
require_once('../controllers/DetteController.php');
require_once('../../connexion/connexion.php');
require_once('../functions/log_dette.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $client = trim($_POST['client']);
    $montant = floatval($_POST['montant']);
    $date = date('Y-m-d H:i:s');
    $auteur = $_SESSION['user_id'] ?? 0;

    // Ajout montant_restant = montant initial
    $dette = new Dette(null, $client, $montant, $date, $auteur, $montant);
   // $dette->montant_restant = $montant; // Ajouté

    $controller = new DetteController($connexion);

    if ($controller->addDette($dette)) {
        $nouvelle = ['client' => $client, 'montant' => $montant];
        enregistrerLogDette($connexion, $auteur, 'ajout', [], $nouvelle);
        $_SESSION['msg'] = "Dette ajoutée avec succès.";
        $_SESSION['type'] = "success";
    } else {
        $_SESSION['msg'] = "Erreur lors de l'ajout.";
        $_SESSION['type'] = "danger";
    }

    header('Location: ../../views/dette.php');
    exit;
}
