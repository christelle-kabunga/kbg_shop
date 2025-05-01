<?php
session_start();
require_once('../../connexion/connexion.php');
require_once('../classes/Stock.php');
require_once('../controllers/StockController.php');
require_once('../functions/log_stock.php'); // Journalisation

$id_produit  = isset($_POST['id_produit']) ? (int)$_POST['id_produit'] : 0;
$mouvement   = $_POST['mouvement'] ?? '';
$quantite    = isset($_POST['quantite']) ? (int)$_POST['quantite'] : 0;

if ($id_produit && $mouvement && $quantite) {

    $date_mouvement = date('Y-m-d H:i:s');
    $utilisateur    = $_SESSION['user_id'] ?? 0;

    $stock      = new Stock($id_produit, $mouvement, $quantite, $date_mouvement, $utilisateur);
    $controller = new StockController($connexion);
    $insertOk   = $controller->ajouterMouvement($stock);

    if (!$insertOk) {
        $err = $connexion->errorInfo();
        die('PDO‑ERROR : ' . $err[2]);
    }

    // LOG STOCK : Ajout
    $nouveau_data = [
        'id_produit'  => $id_produit,
        'mouvement'   => $mouvement,
        'quantite'    => $quantite,
        'date'        => $date_mouvement,
        'nom_produit' => $nom_produit
    ];    

    // Récupération du nom du produit
    $stmt = $connexion->prepare("SELECT nom FROM produit WHERE id = ?");
    $stmt->execute([$id_produit]);
    $nom_produit = $stmt->fetchColumn();
    $nouveau_data['nom_produit'] = $nom_produit;

    enregistrer_log_stock($connexion, 'ajout', [], $nouveau_data, $_SESSION['user_id']);

    $_SESSION['msg']  = 'Mouvement enregistré.';
    $_SESSION['type'] = 'success';
} else {
    $_SESSION['msg']  = 'Tous les champs sont obligatoires.';
    $_SESSION['type'] = 'danger';
}

header('Location: ../../views/stock.php');
exit;
?>