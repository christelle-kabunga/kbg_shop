<?php
session_start();
require_once('../../connexion/connexion.php');
require_once('../classes/produit.php');
require_once('../controllers/ProduitController.php');
require_once('../functions/log_produit.php');   // << utiliser le logger spécial pour produits

// Vérifier si toutes les données sont bien envoyées
if (isset($_POST['nom'], $_POST['id_categorie'], $_POST['seuil'], $_POST['prix'], $_POST['quantite'])) {

    if (
        empty($_POST['nom']) ||
        empty($_POST['id_categorie']) ||
        empty($_POST['seuil']) ||
        empty($_POST['prix']) ||
        empty($_POST['quantite'])
    ) {
        $_SESSION['msg'] = "Veuillez remplir tous les champs.";
        $_SESSION['type'] = "danger";
        header('Location: ../../views/produits.php');
        exit;
    }

    $nom = htmlspecialchars(trim($_POST['nom']));
    $id_categorie = (int) $_POST['id_categorie'];
    $seuil = (int) $_POST['seuil'];
    $prix = (float) $_POST['prix'];
    $quantite = (int) $_POST['quantite'];

    $produit = new Produit($nom, $id_categorie, $seuil, $prix, $quantite);
    $controller = new ProduitController($connexion);
    $result = $controller->ajouterProduit($produit);

    if ($result) {
        // Journaliser l'ajout du produit
        $details = [
            'nom' => $nom,
            //'id_categorie' => $id_categorie,
            'seuil' => $seuil,
            'prix' => $prix,
            'quantite' => $quantite
        ];
        enregistrer_log_produit($connexion, 'ajout', null, json_encode($details));
        
        $_SESSION['msg'] = "Produit ajouté avec succès.";
        $_SESSION['type'] = "success";
    } else {
        $_SESSION['msg'] = "Erreur lors de l'ajout du produit.";
        $_SESSION['type'] = "danger";
    }

    header('Location: ../../views/produits.php');
    exit;
} else {
    header('Location: ../../views/produits.php');
    exit;
}
?>
