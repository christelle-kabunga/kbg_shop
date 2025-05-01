<?php
session_start();
require_once('../../connexion/connexion.php');
require_once('../functions/logger.php');

if (isset($_GET['id']) && ctype_digit($_GET['id'])) {
    $id = (int)$_GET['id'];

    $upd = $connexion->prepare("UPDATE logs SET supprimer = 0 WHERE id = ?");
    $ok  = $upd->execute([$id]);

    if ($ok && $upd->rowCount()) {
        // 1) insérer le log d’audit
        $stmt = $connexion->prepare(
            "INSERT INTO logs (user_id, action) VALUES (?, ?)"
        );
        $stmt->execute([$_SESSION['user_id'] ?? 0, "Désactivation log #$id"]);
        $newId = $connexion->lastInsertId();
    
        // 2) masquer immédiatement cette nouvelle ligne
        $connexion->prepare("UPDATE logs SET supprimer = 0 WHERE id = ?")
                  ->execute([$newId]);
    
        $_SESSION['msg']  = "Log masqué.";
        $_SESSION['type'] = "success";
    }
     else {
        $_SESSION['msg']  = "Impossible de masquer le log.";
        $_SESSION['type'] = "danger";
    }
}
header('Location: ../../views/logs.php');
exit;
