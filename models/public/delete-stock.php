<?php
session_start();
require_once('../../connexion/connexion.php');
require_once('../classes/Stock.php');
require_once('../controllers/StockController.php');
require_once('../functions/log_stock.php');

if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];
    $utilisateur = $_SESSION['user_id'] ?? 0;

    $stockController = new StockController($connexion);
    $ancien = $stockController->getMouvementById($id);

    // ✅ Récupérer le nom du produit AVANT la suppression
    $nom_produit = '';
    if (!empty($ancien['produit_id'])) {
        $stmt = $connexion->prepare("SELECT nom FROM produit WHERE id = ?");
        $stmt->execute([$ancien['produit_id']]);
        $nom_produit = $stmt->fetchColumn() ?: 'Produit inconnu';
    }

    // Préparer les données pour le log AVANT suppression
    $ancien_data = [
        'produit_id'  => $ancien['produit_id'] ?? 0,
        'mouvement'   => $ancien['mouvement'] ?? '',
        'quantite'    => $ancien['quantite'] ?? 0,
        'date'        => $ancien['date_mouvement'] ?? '',
        'nom_produit' => $nom_produit
    ];

    // Maintenant on peut supprimer
    if ($stockController->supprimerMouvement($id)) {
        enregistrer_log_stock($connexion, 'suppression', $ancien_data, [], $utilisateur);
        $_SESSION['msg']  = 'Mouvement supprimé avec succès.';
        $_SESSION['type'] = 'success';
    } else {
        $_SESSION['msg']  = 'Erreur lors de la suppression du mouvement.';
        $_SESSION['type'] = 'danger';
    }
}

header('Location: ../../views/stock.php');
exit;
?>