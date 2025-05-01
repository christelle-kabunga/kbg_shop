<?php
session_start();
require_once('../connexion/connexion.php');

// Récupération des logs produits
$sql = "SELECT lp.*, u.noms AS utilisateur
        FROM log_produit lp
        JOIN utilisateur u ON lp.user_id = u.id
        WHERE lp.supprimer = 1
        ORDER BY lp.date_action DESC";
$logs = $connexion->query($sql)->fetchAll(PDO::FETCH_ASSOC);

// supprimer un log produit
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $connexion->prepare("UPDATE log_produit SET supprimer = 0 WHERE id = ?");
    if ($stmt->execute([$id])) {
        $_SESSION['msg']  = "Log supprimé avec succès.";
        $_SESSION['type'] = "success";
    } else {
        $_SESSION['msg']  = "Erreur lors de la suppression du log.";
        $_SESSION['type'] = "danger";
    }
    header('Location: logs_produit.php');
    exit;
}
?>

<?php require_once('style.php'); ?>
<?php require_once('aside.php'); ?>

<main id="main" class="main">
    <div class="card p-3">
        <h4 class="mb-3">Historique des modifications des produits</h4>

        <?php if (isset($_SESSION['msg'])): ?>
            <div class="alert alert-<?= $_SESSION['type'] ?? 'info' ?> alert-dismissible text-center">
                <?= $_SESSION['msg']; unset($_SESSION['msg'], $_SESSION['type']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="table-responsive">
            <table class="table table-bordered datatable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Utilisateur</th>
                        <th>Action</th>
                        <th>Détails</th>
                        <th>Date</th>
                        <th>Suppr.</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; foreach ($logs as $log): ?>
                        <tr>
                            <td><?= $i++ ?></td>
                            <td><?= htmlspecialchars($log['utilisateur']) ?></td>
                            <td><?= htmlspecialchars($log['action']) ?></td>
                            <td>
                                <?php if ($log['action'] === 'modification'): ?>
                                    <?php
                                        $ancien = json_decode($log['ancien_valeur'], true);
                                        $nouveau = json_decode($log['nouvelle_valeur'], true);
                                    ?>
                                    <strong>Produit :</strong> <?= htmlspecialchars($ancien['nom'] ?? '-') ?><br>
                                    <strong>Avant :</strong><br>
                                    Prix: <?= $ancien['prix'] ?? '-' ?>, Qté: <?= $ancien['quantite'] ?? '-' ?>, Seuil: <?= $ancien['seuil'] ?? '-' ?><br>
                                    <strong>Après :</strong><br>
                                    Prix: <?= $nouveau['prix'] ?? '-' ?>, Qté: <?= $nouveau['quantite'] ?? '-' ?>, Seuil: <?= $nouveau['seuil'] ?? '-' ?>
                                <?php elseif ($log['action'] === 'ajout'): ?>
                                    <strong>Ajout du produit :</strong><br>
                                    <?= htmlspecialchars($log['nouvelle_valeur']) ?>
                                <?php elseif ($log['action'] === 'suppression'): ?>
                                    <strong>Suppression :</strong><br>
                                    <?= htmlspecialchars($log['ancien_valeur']) ?>
                                <?php endif; ?>
                            </td>
                            <td><?= $log['date_action'] ?></td>
                            <td>
                                <a href="logs_produit.php?id=<?= $log['id'] ?>"
                                   onclick="return confirm('Masquer ce log ?')"
                                   class="btn btn-outline-danger btn-sm">
                                    <i class="bi bi-trash3-fill"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<?php require_once('script.php'); ?>
