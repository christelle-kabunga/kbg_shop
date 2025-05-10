<?php
session_start();
require_once('../classes/Depense.php');
require_once('../controllers/DepenseController.php');
require_once('../../connexion/connexion.php');
require_once('../functions/log_depense.php');  // Assurez-vous que ce fichier existe

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $libelle = trim($_POST['libelle']);
    $montant = floatval($_POST['montant']);
    $date = date('Y-m-d H:i:s');
    $auteur = $_SESSION['user_id'] ?? 0;

    $depense = new Depense(null, $libelle, $montant, $date, $auteur);
    $controller = new DepenseController($connexion);
    
    if ($controller->addDepense($depense)) {
        $nouveau_data = [
            'libelle' => $libelle,
            'montant' => $montant
        ];
        
        enregistrerLogDepense($connexion, $auteur, 'ajout', [], $nouveau_data);
        
        $_SESSION['msg'] = "Dépense ajoutée avec succès.";
        $_SESSION['type'] = "success";
    } else {
        $_SESSION['msg'] = "Erreur lors de l'ajout.";
        $_SESSION['type'] = "danger";
    }

    header('Location: ../../views/depense.php');
    exit;
}
