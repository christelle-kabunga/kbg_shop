<?php
session_start();
require_once('../../connexion/connexion.php');
require_once('../classes/Vente.php');
require_once('../controllers/VenteController.php');
require_once('../functions/log_vente.php');

$id         = (int)$_POST['id'];
$idProd     = (int)$_POST['id_produit'];
$ancQte     = (int)$_POST['ancien_quantite'];
$newQte     = (int)$_POST['quantite'];
$prixU      = (float)$_POST['prix_unitaire'];
$date       = date('Y-m-d H:i:s');
$user       = $_SESSION['user_id'] ?? 0;

$diff = $newQte - $ancQte; // Différence à ajuster au stock

try {
    $connexion->beginTransaction();

    // Vérifier l'existence de la vente
    $stmt = $connexion->prepare("
        SELECT v.quantite, v.prix_unitaire, v.total, p.nom AS nom_produit, p.quantite AS stock_produit
        FROM vente v
        JOIN produit p ON v.produit_id = p.id
        WHERE v.id = ?
    ");
    $stmt->execute([$id]);
    $venteData = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$venteData) {
        throw new Exception("Vente introuvable.");
    }

    // Vérifier que le stock suffit (en cas d'augmentation de quantité)
    if ($diff > 0 && $diff > $venteData['stock_produit']) {
        throw new Exception("Stock insuffisant pour augmenter la quantité. Stock disponible: {$venteData['stock_produit']}");
    }

    // Sauvegarde ancienne
    $ancien_data = [
        'quantite' => $venteData['quantite'],
        'prix_unitaire' => $venteData['prix_unitaire'],
        'total' => $venteData['total'],
        'nom_produit' => $venteData['nom_produit'],
    ];

    // Mettre à jour la vente
    $total = $newQte * $prixU;
    $up = $connexion->prepare("
        UPDATE vente 
        SET quantite = ?, prix_unitaire = ?, total = ?, date_vente = ? 
        WHERE id = ?
    ");
    $up->execute([$newQte, $prixU, $total, $date, $id]);

    // Mettre à jour le stock
    $updateStock = $connexion->prepare("
        UPDATE produit 
        SET quantite = quantite - ? 
        WHERE id = ?
    ");
    $updateStock->execute([$diff, $idProd]);

    // Journaliser
    $nouveau_data = [
        'quantite' => $newQte,
        'prix_unitaire' => $prixU,
        'total' => $total,
        'nom_produit' => $venteData['nom_produit'],
    ];

    enregistrer_log($connexion, 'modification', $ancien_data, $nouveau_data, $user);

    $connexion->commit();

    $_SESSION['msg']  = "Vente modifiée avec succès.";
    $_SESSION['type'] = "success";
} catch (Exception $e) {
    $connexion->rollBack();
    $_SESSION['msg']  = "Erreur : " . $e->getMessage();
    $_SESSION['type'] = "danger";
}

header('Location: ../../views/ventes.php');
exit;
?>
