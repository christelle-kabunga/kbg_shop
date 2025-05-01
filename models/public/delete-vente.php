<?php
session_start();
require_once('../../connexion/connexion.php');
require_once('../controllers/VenteController.php');
require_once('../functions/log_vente.php');

$id = (int)$_GET['id'];
$vc = new VenteController($connexion);

// Récupérer les anciennes valeurs + nom du produit avant suppression
$stmt = $connexion->prepare("
    SELECT p.nom, v.quantite, v.prix_unitaire 
    FROM vente v
    JOIN produit p ON v.produit_id = p.id
    WHERE v.id = ?
");
$stmt->execute([$id]);
$venteData = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$venteData) {
    $_SESSION['msg']  = "Erreur: Vente introuvable.";
    $_SESSION['type'] = "danger";
    header('Location: ../../views/ventes.php');
    exit;
}

if ($vc->supprimerVente($id)) {
    // Sauvegarder les anciennes valeurs pour le log
    $ancien_data = [
        'nom_produit' => $venteData['nom'],
        'quantite' => $venteData['quantite'],
        'prix_unitaire' => $venteData['prix_unitaire'],
    ];

    $nouveau_data = [
        'nom_produit' => $venteData['nom'],
        'quantite' => 0, // La vente est supprimée donc quantite 0
        'prix_unitaire' => 0, // La vente est supprimée donc prix 0
    ];

    // 4) Journalisation propre
    enregistrer_log($connexion, 'suppression', $ancien_data, $nouveau_data, $_SESSION['user_id'] ?? 0);

    $_SESSION['msg']  = "Vente masquée.";
    $_SESSION['type'] = "success";
}

header('Location: ../../views/ventes.php');
exit;
?>
