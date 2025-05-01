<?php
// Connexion à la base de données
require_once(__DIR__ . '/../../connexion/connexion.php');
require_once('../functions/log_produit.php'); // Inclusion de la classe Produit
// Inclusion de la classe Produit et ProduitController
require_once(__DIR__ . '/../classes/produit.php');
require_once(__DIR__ . '/../controllers/ProduitController.php');

// Récupérer les données du formulaire
$id = $_POST['id'];
$nom = $_POST['nom'];
$prix = $_POST['prix'];
$quantite = $_POST['quantite'];
$seuil = $_POST['seuil'];
$id_categorie = $_POST['id_categorie'];

// Créer un objet Produit avec tous les paramètres requis
$produit = new Produit($nom, $id_categorie, $seuil, $prix, $quantite, $id);

// Appeler le contrôleur pour mettre à jour le produit
$produitController = new ProduitController($connexion);
if ($produitController->modifierProduit($produit)) {
    // Récupérer les anciennes valeurs pour comparaison
    $stmtOld = $connexion->prepare("SELECT * FROM produit WHERE id = ?");
    $stmtOld->execute([$id]);
    $ancien = $stmtOld->fetch(PDO::FETCH_ASSOC);

    // Ce que l'on modifie et les nouvelles valeurs
    $nouveau = [
        'nom' => $nom,
        'prix' => $prix,
        'quantite' => $quantite,
        'seuil' => $seuil,
        'categorie' => $id_categorie
    ];

    // Récupérer l'utilisateur
    session_start();
    $user_id = $_SESSION['user_id'] ?? 0;

    // Log personnalisé
    require_once(__DIR__ . '/../functions/logger.php');
    log_produit($connexion, $user_id, 'modification', $ancien, $nouveau);

    $_SESSION['msg'] = 'Produit mis à jour avec succès';
    $_SESSION['type'] = 'success';
    header('Location: ../../views/produits.php');
    exit;
}
else {
    $_SESSION['msg'] = 'Erreur lors de la mise à jour du produit';
    $_SESSION['type'] = 'danger';
    header('Location: ../../views/produits.php');
    exit;
}
?>
