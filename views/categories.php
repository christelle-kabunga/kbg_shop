<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

require_once('../connexion/connexion.php');
require_once('../models/controllers/CategorieController.php');

$controller = new CategorieController($connexion);
$categories = $controller->getCategories();
?>

<?php require_once('style.php'); ?>
<?php require_once('aside.php'); ?>

<main id="main" class="main">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>Catégories</h4>
        <button class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#addCategorieModal">
            Ajouter une catégorie
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
                    <th>Nom</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $i = 1; foreach ($categories as $cat): ?>
                    <tr>
                        <th><?= $i++ ?></th>
                        <td><?= htmlspecialchars($cat['nom']) ?></td>
                        <td>
                            <a href="#" class="btn btn-dark btn-sm" data-bs-toggle="modal" data-bs-target="#editCategorieModal<?= $cat['id'] ?>">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <a onclick="return confirm('Supprimer cette catégorie ?')" href="../models/public/delete-categorie.php?id=<?= $cat['id'] ?>" class="btn btn-danger btn-sm">
                                <i class="bi bi-trash3-fill"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>

    <!-- 🔽 MODALES DE MODIFICATION PLACÉES EN DEHORS DE LA TABLE -->
    <?php foreach ($categories as $cat): ?>
        <div class="modal fade" id="editCategorieModal<?= $cat['id'] ?>" tabindex="-1" aria-labelledby="editCategorieModalLabel<?= $cat['id'] ?>" aria-hidden="true">
            <div class="modal-dialog">
                <form action="../models/public/update-categorie-post.php" method="post" class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editCategorieModalLabel<?= $cat['id'] ?>">Modifier la catégorie</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" value="<?= $cat['id'] ?>">
                        <label for="nomCategorie<?= $cat['id'] ?>" class="form-label">Nom</label>
                        <input type="text" name="libelle" id="nomCategorie<?= $cat['id'] ?>" class="form-control" value="<?= htmlspecialchars($cat['nom']) ?>" required>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-dark">Modifier</button>
                    </div>
                </form>
            </div>
        </div>
    <?php endforeach; ?>
</main>

<!-- Modal ajout -->
<div class="modal fade" id="addCategorieModal" tabindex="-1" aria-labelledby="addCategorieModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="../models/public/add-categorie-post.php" method="post" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nouvelle catégorie</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <label for="nomCategorie" class="form-label">Nom</label>
                <input type="text" name="nom" id="nomCategorie" class="form-control" required>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-dark">Ajouter</button>
            </div>
        </form>
    </div>
</div>

<?php require_once('script.php'); ?>
