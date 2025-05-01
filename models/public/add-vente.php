<?php
session_start();
require_once('../../connexion/connexion.php');
require_once('../classes/Vente.php');
require_once('../controllers/VenteController.php');
require_once('../functions/log_vente.php');

$id_produit = (int)$_POST['id_produit'];
$quantite   = (int)$_POST['quantite'];
$prixU      = (float)$_POST['prix_unitaire'];
$date       = date('Y-m-d H:i:s');
$user       = $_SESSION['user_id'] ?? 0;
$total      = $quantite * $prixU;

try {
    $connexion->beginTransaction();

    // 1. Vérifier le stock disponible
    $stmt = $connexion->prepare("SELECT nom, quantite FROM produit WHERE id = ?");
    $stmt->execute([$id_produit]);
    $produit = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$produit) {
        throw new Exception("Produit introuvable.");
    }

    if ($quantite > $produit['quantite']) {
        throw new Exception("Quantité demandée dépasse le stock disponible.");
    }

    // 2. Ajouter la vente (pas de vérification de doublon)
    $stmt = $connexion->prepare("
        INSERT INTO vente (produit_id, quantite, prix_unitaire, total, date_vente, utilisateur)
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    $stmt->execute([$id_produit, $quantite, $prixU, $total, $date, $user]);

    // 3. Mettre à jour le stock
    $update = $connexion->prepare("UPDATE produit SET quantite = quantite - ? WHERE id = ?");
    $update->execute([$quantite, $id_produit]);

    // 4. Journalisation
    $ancien_data = [
        'nom_produit' => $produit['nom'],
        'quantite' => 0,
        'prix_unitaire' => 0,
        'total' => 0,
    ];

    $nouveau_data = [
        'nom_produit' => $produit['nom'],
        'quantite' => $quantite,
        'prix_unitaire' => $prixU,
        'total' => $total,
    ];

    enregistrer_log($connexion, 'ajout', $ancien_data, $nouveau_data, $user);

    $connexion->commit();

    $_SESSION['msg']  = "Vente enregistrée.";
    $_SESSION['type'] = "success";

} catch (Exception $e) {
    if ($connexion->inTransaction()) {
        $connexion->rollBack();
    }
    $_SESSION['msg']  = "Erreur : " . $e->getMessage();
    $_SESSION['type'] = "danger";
}

header('Location: ../../views/ventes.php');
exit;
?>
