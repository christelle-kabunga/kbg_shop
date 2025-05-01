<?php
session_start();
require_once('../../connexion/connexion.php');
require_once('../controllers/ProduitController.php');
require_once('../functions/log_produit.php');  // << utiliser logger_produit

if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];
    $controller = new ProduitController($connexion);

    // Avant suppression, récupérer les anciennes données
    $produit = $controller->getProduitById($id); // Crée cette fonction dans ton controller si pas encore
    $ancien = [
        'nom' => $produit['nom'],
       // 'id_categorie' => $produit['id_categorie'],
        'seuil' => $produit['seuil'],
        'prix' => $produit['prix'],
        'quantite' => $produit['quantite']
    ];

    if ($controller->supprimerProduit($id)) {
        // Journaliser la suppression
        enregistrer_log_produit($connexion, 'suppression', json_encode($ancien), null);

        $_SESSION['msg'] = "Produit supprimé avec succès.";
        $_SESSION['type'] = "success";
    } else {
        $_SESSION['msg'] = "Erreur lors de la suppression.";
        $_SESSION['type'] = "danger";
    }
} else {
    $_SESSION['msg'] = "ID de produit manquant.";
}

header('Location: ../../views/produits.php');
exit;
?>
