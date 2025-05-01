<?php
session_start();
require_once('../connexion/connexion.php');

// Masquer un log de stock
if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];
    $stmt = $connexion->prepare("UPDATE log_stock SET supprimer = 1 WHERE id = ?");
    if ($stmt->execute([$id])) {
        $_SESSION['msg']  = "Log de stock masqué avec succès.";
        $_SESSION['type'] = "success";
    } else {
        $_SESSION['msg']  = "Erreur lors du masquage du log.";
        $_SESSION['type'] = "danger";
    }
    header('Location: logs_stock.php');
    exit;
}

// Récupération des logs de stock non masqués
$sql = "SELECT ls.*, u.noms AS utilisateur
        FROM log_stock ls
        LEFT JOIN utilisateur u ON ls.utilisateur = u.id
        WHERE ls.supprimer = 0
        ORDER BY ls.date_action DESC";
$logs = $connexion->query($sql)->fetchAll(PDO::FETCH_ASSOC);
?>

<?php require_once('style.php'); ?>
<?php require_once('aside.php'); ?>

<main id="main" class="main">
    <div class="card p-3">
        <h4 class="mb-3">Historique des opérations sur le stock</h4>

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
                                <td><?= htmlspecialchars($log['action']) ?></td>
                                <td>
                                <?php
                                    $details = json_decode($log['details'], true);
                                    
                                    // Tentative de récupération du nom du produit depuis plusieurs emplacements
                                    $nom_produit =
                                        $details['produit'] ??
                                        $details['nom_produit'] ??
                                        $details['donnees_nouvelles']['nom_produit'] ??
                                        $details['donnees_anciennes']['nom_produit'] ??
                                        '-';

                                    $ancien  = $details['donnees_anciennes'] ?? null;
                                    $nouveau = $details['donnees_nouvelles'] ?? null;

                                    // Affichage des détails de l'action
                                    if ($log['action'] === 'modification' && $ancien && $nouveau): ?>
                                        <strong>Produit :</strong> <?= $nom_produit ?><br>
                                        <strong>Avant :</strong><br>
                                        Mouvement : <?= $ancien['mouvement'] ?? '-' ?><br>
                                        Quantité : <?= $ancien['quantite'] ?? '-' ?><br>

                                        <strong>Après :</strong><br>
                                        Mouvement : <?= $nouveau['mouvement'] ?? '-' ?><br>
                                        Quantité : <?= $nouveau['quantite'] ?? '-' ?><br>

                                    <?php elseif ($log['action'] === 'ajout' && $nouveau): ?>
                                        <strong>Produit :</strong> <?= $nom_produit ?><br>
                                        <strong>Ajout :</strong><br>
                                        Mouvement : <?= $nouveau['mouvement'] ?? '-' ?><br>
                                        Quantité : <?= $nouveau['quantite'] ?? '-' ?><br>

                                    <?php elseif ($log['action'] === 'suppression' && $ancien): ?>
                                        <strong>Produit :</strong> <?= $nom_produit ?><br>
                                        <strong>Suppression :</strong><br>
                                        Mouvement : <?= $ancien['mouvement'] ?? '-' ?><br>
                                        Quantité : <?= $ancien['quantite'] ?? '-' ?><br>

                                    <?php else: ?>
                                        <em>Données indisponibles</em>
                                    <?php endif; ?>
                            </td>
                                <td><?= $log['date_action'] ?></td>
                                <td>
                                    <a  onclick="return confirm('Masquer ce log ?')"
                                        href="logs_stock.php?id=<?= $log['id'] ?>"
                                        class="btn btn-outline-danger btn-sm" title="Masquer">
                                        <i class="bi bi-trash3-fill"></i>
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
