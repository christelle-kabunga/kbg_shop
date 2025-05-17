<?php
require_once '../connexion/connexion.php';

$type = $_GET['type'] ?? '';
$filter = $_GET['filter'] ?? 'all';

function filtreDate(string $champDate, string $filtre): string {
    switch ($filtre) {
        case 'today':
            return "DATE($champDate) = CURDATE()";
        case 'month':
            return "MONTH($champDate) = MONTH(CURDATE()) AND YEAR($champDate) = YEAR(CURDATE())";
        case 'year':
            return "YEAR($champDate) = YEAR(CURDATE())";
        default:
            return "1"; // pas de filtre
    }
}

switch ($type) {
    case 'produits':
        $sql = "SELECT COUNT(*) FROM produit WHERE " . filtreDate('created_at', $filter);
        break;

    case 'stock':
        $sql = "SELECT SUM(quantite) FROM stock WHERE " . filtreDate('date_mouvement', $filter);
        break;

    case 'ventes':
        $sql = "SELECT SUM(total) FROM vente WHERE " . filtreDate('date_vente', $filter);
        break;

    case 'dettes':
        $sql = "SELECT SUM(montant) FROM dette WHERE " . filtreDate('date_dette', $filter);
        break;

    case 'depenses':
        $sql = "SELECT SUM(montant) FROM depenses WHERE " . filtreDate('date_depense', $filter);
        break;

     case 'paiement_dette':
         $sql = "SELECT SUM(montant_paye) FROM paiement_dette WHERE " . filtreDate('date_paiement', $filter);
         break;
        
    default:
        echo 0;
        exit;
}

$stmt = $connexion->query($sql);
echo $stmt->fetchColumn() ?? 0;
