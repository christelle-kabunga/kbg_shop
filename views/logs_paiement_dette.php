<?php
session_start();
require_once('../connexion/connexion.php');

// Filtre selon la demande (afficher tous ou non supprimés uniquement)
$afficher_tout = isset($_GET['afficher_tout']) && $_GET['afficher_tout'] == 1;
$filtre = $afficher_tout ? '' : 'WHERE lp.supprimer = 1';

// Récupération des logs de paiement de dettes
$sql = "SELECT lp.*, u.noms AS utilisateur
        FROM log_paiement_dette lp
        JOIN utilisateur u ON lp.utilisateur_id = u.id
        $filtre
        ORDER BY lp.date_action DESC";
$logs = $connexion->query($sql)->fetchAll(PDO::FETCH_ASSOC);

// Suppression logique (masquage) d’un log
if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Sécurisation
    $stmt = $connexion->prepare("UPDATE log_paiement_dette SET supprimer = 0 WHERE id = ?");
    if ($stmt->execute([$id])) {
        $_SESSION['msg'] = "Log supprimé avec succès.";
        $_SESSION['type'] = "success";
    } else {
        $_SESSION['msg'] = "Erreur lors de la suppression du log.";
        $_SESSION['type'] = "danger";
    }
    header('Location: logs_paiement_dette.php');
    exit;
}
?>
<?php require_once('style.php'); ?>
<?php require_once('aside.php'); ?>

<main id="main" class="main">
    <div class="card p-3">
        <h4 class="mb-3 d-flex justify-content-between">
            <span>Historique des paiements de dettes</span>
            <a href="?afficher_tout=<?= $afficher_tout ? 0 : 1 ?>" class="btn btn-sm btn-outline-primary">
                <?= $afficher_tout ? 'Masquer les logs supprimés' : 'Afficher tous les logs' ?>
            </a>
        </h4>

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
                    <?php if (empty($logs)): ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted">Aucun log trouvé.</td>
                        </tr>
                    <?php else: ?>
                        <?php $i = 1; foreach ($logs as $log): ?>
                            <tr>
                                <td><?= $i++ ?></td>
                                <td><?= htmlspecialchars($log['utilisateur']) ?></td>
                                <td><?= htmlspecialchars($log['action']) ?></td>
                                <td><?= nl2br(htmlspecialchars($log['details'])) ?></td>
                                <td><?= $log['date_action'] ?></td>
                                <td>
                                    <?php if ($log['supprimer'] == 1): ?>
                                        <a href="logs_paiement_dette.php?id=<?= $log['id'] ?>"
                                           onclick="return confirm('Masquer ce log ?')"
                                           class="btn btn-outline-danger btn-sm">
                                            <i class="bi bi-trash3-fill"></i>
                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted">Masqué</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<?php require_once('script.php'); ?>
