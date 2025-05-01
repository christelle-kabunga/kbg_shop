<?php
session_start();
require_once('../connexion/connexion.php');

// Récupération des logs
$sql = "SELECT l.*, u.noms AS utilisateur
        FROM logs l
        JOIN utilisateur u ON l.user_id = u.id
        WHERE l.supprimer = 1           -- n’affiche que les logs actifs
        ORDER BY l.date_action DESC";
$logs = $connexion->query($sql)->fetchAll(PDO::FETCH_ASSOC);

?>

<?php require_once('style.php'); ?>
<?php require_once('aside.php'); ?>

<main id="main" class="main">
    <div class="card p-3">
        <h4 class="mb-3">Historique des actions</h4>

        <?php if (isset($_SESSION['msg'])): ?>
            <div class="alert alert-<?= $_SESSION['type'] ?? 'info' ?> alert-dismissible text-center">
                <?= $_SESSION['msg']; unset($_SESSION['msg'], $_SESSION['type']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="table-responsive">
            <table class="table table-borderless datatable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Utilisateur</th>
                        <th>Action</th>
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
                            <td><?= $log['date_action'] ?></td>
                            <td class="text-nowrap">
                                <a  onclick="return confirm('Masquer ce log ?')"
                                href="../models/public/delete-log.php?id=<?= $log['id'] ?>"
                                    class="btn btn-outline-danger btn-sm" title="Masquer">
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
