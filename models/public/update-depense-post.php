<?php
session_start();
require_once('../classes/Depense.php');
require_once('../controllers/DepenseController.php');
require_once('../../connexion/connexion.php');
require_once('../functions/log_depense.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $libelle = trim($_POST['libelle']);
    $montant = floatval($_POST['montant']);
    $date = date('Y-m-d H:i:s');
    $auteur = $_SESSION['user_id'] ?? 0;

    $controller = new DepenseController($connexion);

    // Pour log : récupérer l'ancienne dépense
    $depenses = $controller->getDepenses(); // ou créer une méthode getDepenseById() si tu préfères
    $ancienne = null;
    foreach ($depenses as $d) {
        if ($d['depense_id'] == $id) {
            $ancienne = [
                'libelle' => $d['motif'],
                'montant' => $d['montant']
            ];
            break;
        }
    }

    // Instanciation correcte
    $depense = new Depense($id, $libelle, $montant, $date, $auteur);

    if ($controller->updateDepense($id, $depense)) {
        $nouvelle = [
            'libelle' => $libelle,
            'montant' => $montant
        ];
        enregistrerLogDepense($connexion, $auteur, 'modification', $ancienne ?? [], $nouvelle);
        $_SESSION['msg'] = "Dépense modifiée avec succès.";
        $_SESSION['type'] = "success";
    } else {
        $_SESSION['msg'] = "Erreur lors de la modification.";
        $_SESSION['type'] = "danger";
    }

    header('Location: ../../views/depense.php');
    exit;
}
