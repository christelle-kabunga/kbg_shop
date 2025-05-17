<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

require_once('../connexion/connexion.php');
require_once('../models/controllers/DetteController.php');

$controller = new DetteController($connexion);
$dettes = $controller->getDettes();
?>

<?php require_once('style.php'); ?>
<?php require_once('aside.php'); ?>

<main id="main" class="main">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>Liste des Dettes</h4>
        <button class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#addDetteModal">
            Ajouter une dette
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
                    <th>Client</th>
                    <th>Montant</th>
                    <th>Montant restant</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $i = 1; foreach ($dettes as $dette): ?>
                <tr>
                    <th><?= $i++ ?></th>
                    <td><?= htmlspecialchars($dette['client']) ?></td>
                    <td><?= htmlspecialchars($dette['montant']) ?> FC</td>
                    <td><?= number_format($dette['montant_restant']) ?> FC</td>
                    <td><?= htmlspecialchars($dette['date_dette']) ?></td>
                    <td>
                        <a href="#" class="btn btn-dark btn-sm" data-bs-toggle="modal" data-bs-target="#editDetteModal<?= $dette['id'] ?>">
                            <i class="bi bi-pencil-square"></i>
                        </a>
                        <a onclick="return confirm('Supprimer cette dette ?')" href="../models/public/delete-dette.php?id=<?= $dette['id'] ?>" class="btn btn-danger btn-sm">
                            <i class="bi bi-trash3-fill"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- ✅ MODALES DE MODIFICATION -->
    <?php foreach ($dettes as $dette): ?>
    <div class="modal fade" id="editDetteModal<?= $dette['id'] ?>" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form action="../models/public/update-dette-post.php" method="post" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modifier la dette</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" value="<?= $dette['id'] ?>">
                    <div class="mb-3">
                        <label class="form-label">Client</label>
                        <input type="text" name="client" class="form-control" value="<?= htmlspecialchars($dette['client']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Montant</label>
                        <input type="number" step="0.01" name="montant" class="form-control" value="<?= $dette['montant'] ?>" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-dark">Modifier</button>
                </div>
            </form>
        </div>
    </div>
    <?php endforeach; ?>

    <!-- ✅ MODALE D'AJOUT -->
    <div class="modal fade" id="addDetteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form action="../models/public/add-dette-post.php" method="post" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Nouvelle dette</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Client</label>
                        <input type="text" name="client" class="form-control" required>
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
</main>

<?php require_once('script.php'); ?>
