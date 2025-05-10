<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

require_once('../connexion/connexion.php');
require_once('../models/controllers/DepenseController.php');

$controller = new DepenseController($connexion);
$depenses = $controller->getDepenses();
?>

<?php require_once('style.php'); ?>
<?php require_once('aside.php'); ?>

<main id="main" class="main">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>Liste des Dépenses</h4>
        <button class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#addDepenseModal">
            Ajouter une dépense
        </button>
    </div>

    <?php if (isset($_SESSION['msg'])): ?>
        <div class="alert alert-<?= $_SESSION['type'] ?> alert-dismissible fade show text-center" role="alert">
            <?= $_SESSION['msg'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
        </div>
        <?php unset($_SESSION['msg'], $_SESSION['type']); ?>
    <?php endif; ?>

    <div class="col-xl-12 table-responsive px-3 card mt-4 px-4 pt-3">
    <table class="table table-borderless datatable">
            <thead>
                <tr>
                    <th>N°</th>
                    <th>Libellé</th>
                    <th>Montant</th>
                    <th>Date</th>
                    <th>Auteur</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
    <?php $i = 1; foreach ($depenses as $dep): ?>
    <tr>
        <th><?= $i++ ?></th>
        <td><?= htmlspecialchars($dep['motif']) ?></td>
        <td><?= number_format($dep['montant'], 2) ?> $</td>
        <td><?= htmlspecialchars($dep['date_depense']) ?></td>
        <td><?= htmlspecialchars($dep['noms']) ?></td>
        <td>
            <a href="#" class="btn btn-dark btn-sm" data-bs-toggle="modal" data-bs-target="#editDepenseModal<?= $dep['depense_id'] ?>">
                <i class="bi bi-pencil-square"></i>
            </a>
            <a onclick="return confirm('Supprimer cette dépense ?')" href="../models/public/delete-depense.php?id=<?= $dep['depense_id'] ?>" class="btn btn-danger btn-sm">
                <i class="bi bi-trash3-fill"></i>
            </a>
        </td>
    </tr>
    <?php endforeach; ?>
</tbody>
</table>
</div> <!-- fin div.table-responsive -->

<!-- ✅ MODALES DE MODIFICATION — À l'extérieur de la table -->
<?php foreach ($depenses as $dep): ?>
<div class="modal fade" id="editDepenseModal<?= $dep['depense_id'] ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="../models/public/update-depense-post.php" method="post" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modifier la dépense</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="id" value="<?= $dep['depense_id'] ?>">
                <div class="mb-3">
                    <label class="form-label">Libellé</label>
                    <input type="text" name="libelle" class="form-control" value="<?= htmlspecialchars($dep['motif']) ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Montant</label>
                    <input type="number" step="0.01" name="montant" class="form-control" value="<?= $dep['montant'] ?>" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-dark">Modifier</button>
            </div>
        </form>
    </div>
</div>
<?php endforeach; ?>
</main>

<!-- ✅ MODALE D'AJOUT -->
<div class="modal fade" id="addDepenseModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="../models/public/add-depense-post.php" method="post" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nouvelle dépense</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Libellé</label>
                    <input type="text" name="libelle" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Montant</label>
                    <input type="number" step="0.01" name="montant" class="form-control" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-dark">Ajouter</button>
            </div>
        </form>
    </div>
</div>

<?php require_once('script.php'); ?>
