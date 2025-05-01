<?php
session_start();
require_once('../../connexion/connexion.php');
require_once('../classes/Stock.php');
require_once('../controllers/StockController.php');
require_once('../functions/log_stock.php'); // journalisation stock

$id          = isset($_POST['id'])         ? (int)$_POST['id']         : 0;
$id_produit  = isset($_POST['id_produit']) ? (int)$_POST['id_produit'] : 0;
$mouvement   = $_POST['mouvement'] ?? '';
$quantite    = isset($_POST['quantite'])   ? (int)$_POST['quantite']   : 0;

$utilisateur = $_SESSION['user_id'] ?? 0;
$date        = date('Y-m-d H:i:s');

if ($id && $id_produit && $mouvement && $quantite) {
    // Récupération des anciennes données
    $stmt_old = $connexion->prepare("SELECT * FROM stock WHERE id = ?");
    $stmt_old->execute([$id]);
    $ancien_data = $stmt_old->fetch(PDO::FETCH_ASSOC);

    // Récupérer le nom du produit de l'ID dans les anciennes données
    $stmt_prod = $connexion->prepare("SELECT nom FROM produit WHERE id = ?");
    $stmt_prod->execute([$ancien_data['produit_id']]);
    $nom_produit = $stmt_prod->fetchColumn();

    // Préparer les nouvelles données
    $nouveau_data = [
        'id_produit'  => $id_produit,
        'mouvement'   => $mouvement,
        'quantite'    => $quantite,
        'date_action' => $date
    ];

    // Ajout du nom produit
    $ancien_data['nom_produit']  = $nom_produit;
    $nouveau_data['nom_produit'] = $nom_produit;

    // Création de l'objet Stock
    $stock = new Stock($id_produit, $mouvement, $quantite, $date, $utilisateur, $id);
    $controller = new StockController($connexion);

    if ($controller->modifierMouvement($stock)) {
        // Enregistrement du log
        enregistrer_log_stock($connexion, 'modification', $ancien_data, $nouveau_data, $utilisateur);

        $_SESSION['msg']  = 'Mouvement modifié.';
        $_SESSION['type'] = 'success';
    } else {
        $_SESSION['msg']  = 'Erreur SQL lors de la modification.';
        $_SESSION['type'] = 'danger';
    }
} else {
    $_SESSION['msg']  = 'Champs manquants.';
    $_SESSION['type'] = 'danger';
}

header('Location: ../../views/stock.php');
exit;
?>