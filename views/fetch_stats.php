<?php
require_once('connexion.php'); // Connexion à la base

$type = $_GET['type'] ?? '';
$filter = $_GET['filter'] ?? 'today';

$dateFilter = "";
switch($filter) {
    case 'today':
        $dateFilter = "DATE(date_operation) = CURDATE()";
        break;
    case 'month':
        $dateFilter = "MONTH(date_operation) = MONTH(CURDATE()) AND YEAR(date_operation) = YEAR(CURDATE())";
        break;
    case 'year':
        $dateFilter = "YEAR(date_operation) = YEAR(CURDATE())";
        break;
    case 'all':
        $dateFilter = "1"; // Pas de filtre
        break;
    default:
        $dateFilter = "DATE(date_operation) = CURDATE()";
}

$label = ucfirst($filter);
$total = 0;

switch ($type) {
    case 'produits':
        $query = $conn->prepare("SELECT COUNT(*) as total FROM produits");
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);
        $total = $result['total'] ?? 0;
        break;

    case 'ventes':
        $query = $conn->prepare("SELECT SUM(prix_vente * quantite) as total FROM ventes WHERE $dateFilter");
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);
        $total = $result['total'] ?? 0;
        break;

    case 'stock':
        $query = $conn->prepare("SELECT SUM(quantite) as total FROM stock WHERE type_mouvement = 'entrée' AND $dateFilter");
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);
        $total = $result['total'] ?? 0;
        break;

    case 'dettes':
        $query = $conn->prepare("SELECT SUM(montant) as total FROM dettes WHERE $dateFilter");
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);
        $total = $result['total'] ?? 0;
        break;

    case 'depenses':
        $query = $conn->prepare("SELECT SUM(montant) as total FROM depenses WHERE $dateFilter");
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);
        $total = $result['total'] ?? 0;
        break;

    case 'benefices':
        // Bénéfices = ventes - dépenses (optionnellement dettes)
        $qVentes = $conn->prepare("SELECT SUM(prix_vente * quantite) as total FROM ventes WHERE $dateFilter");
        $qVentes->execute();
        $v = $qVentes->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

        $qDep = $conn->prepare("SELECT SUM(montant) as total FROM depenses WHERE $dateFilter");
        $qDep->execute();
        $d = $qDep->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

        $total = $v - $d;
        break;
}

echo json_encode([
    'value' => round($total, 2),
    'label' => '| ' . $label
]);
