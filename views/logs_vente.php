<?php
session_start();
require_once('../connexion/connexion.php');

// Masquer un log de vente
if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];
    $stmt = $connexion->prepare("UPDATE log_vente SET supprimer = 1 WHERE id = ?");
    if ($stmt->execute([$id])) {
        $_SESSION['msg']  = "Log de vente masqué avec succès.";
        $_SESSION['type'] = "success";
    } else {
        $_SESSION['msg']  = "Erreur lors du masquage du log.";
        $_SESSION['type'] = "danger";
    }
    header('Location: logs_vente.php');
    exit;
}

// Récupération des logs vente non masqués
$sql = "SELECT lv.*, u.noms AS utilisateur
        FROM log_vente lv
        LEFT JOIN utilisateur u ON lv.id_utilisateur = u.id
        WHERE lv.supprimer = 0
        ORDER BY lv.date_operation DESC";
$logs = $connexion->query($sql)->fetchAll(PDO::FETCH_ASSOC);
?>

<?php require_once('style.php'); ?>
<?php require_once('aside.php'); ?>

<main id="main" class="main">
    <div class="card p-3">
        <h4 class="mb-3">Historique des opérations sur les ventes</h4>

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
                        <th>Masquer</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($logs)): ?>
                        <?php $i = 1; foreach ($logs as $log): ?>
                            <tr>
                                <td><?= $i++ ?></td>
                                <td><?= htmlspecialchars($log['utilisateur'] ?? '-') ?></td>
                                <td><?= htmlspecialchars($log['type_operation']) ?></td>
                                <td>
                                    <?php
                                        $ancien = $log['donnees_anciennes'] ? json_decode($log['donnees_anciennes'], true) : null;
                                        $nouveau = $log['donnees_nouvelles'] ? json_decode($log['donnees_nouvelles'], true) : null;
                                    ?>

                                    <?php if ($log['type_operation'] === 'modification' && $ancien && $nouveau): ?>
                                        <strong>Avant :</strong><br>
                                        Produit : <?= $ancien['nom_produit'] ?? '-' ?><br>
                                        Quantité : <?= $ancien['quantite'] ?? '-' ?><br>
                                        Prix unitaire : <?= $ancien['prix_unitaire'] ?? '-' ?><br>
                                        Total : <?= $ancien['total'] ?? '-' ?><br>

                                        <strong>Après :</strong><br>
                                        Produit : <?= $nouveau['nom_produit'] ?? '-' ?><br>
                                        Quantité : <?= $nouveau['quantite'] ?? '-' ?><br>
                                        Prix unitaire : <?= $nouveau['prix_unitaire'] ?? '-' ?><br>
                                        Total : <?= $nouveau['total'] ?? '-' ?><br>

                                    <?php elseif ($log['type_operation'] === 'ajout' && $nouveau): ?>
                                        <strong>Nouvelle vente :</strong><br>
                                        Produit : <?= $nouveau['nom_produit'] ?? '-' ?><br>
                                        Quantité : <?= $nouveau['quantite'] ?? '-' ?><br>
                                        Prix unitaire : <?= $nouveau['prix_unitaire'] ?? '-' ?><br>
                                        Total : <?= $nouveau['total'] ?? '-' ?><br>

                                    <?php elseif ($log['type_operation'] === 'suppression' && $ancien): ?>
                                        <strong>Suppression :</strong><br>
                                        Produit : <?= $ancien['nom_produit'] ?? '-' ?><br>
                                        Quantité : <?= $ancien['quantite'] ?? '-' ?><br>
                                        Prix unitaire : <?= $ancien['prix_unitaire'] ?? '-' ?><br>
                                        Total : <?= $ancien['total'] ?? '-' ?><br>
                                    <?php else: ?>
                                        <em>Données indisponibles</em>
                                    <?php endif; ?>
                                </td>
                                <td><?= $log['date_operation'] ?></td>
                                <td>
                                    <a href="logs_vente.php?id=<?= $log['id'] ?>" class="btn btn-outline-danger btn-sm"
                                       onclick="return confirm('Masquer ce log de vente ?')">
                                        <i class="bi bi-eye-slash-fill"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    <?php else: ?>
                        <tr><td colspan="6" class="text-center">Aucun log trouvé.</td></tr>
                    <?php endif ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<?php require_once('script.php'); ?>
