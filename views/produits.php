<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

require_once('../connexion/connexion.php');
require_once('../models/controllers/ProduitController.php');
require_once('../models/controllers/CategorieController.php');

$produitController = new ProduitController($connexion);
$categorieController = new CategorieController($connexion);

$produits = $produitController->getProduits();
$categories = $categorieController->getCategories();
?>

<?php require_once('style.php'); ?>
<?php require_once('aside.php'); ?>

<main id="main" class="main">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>Produits</h4>
        <button class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#addProduitModal">
            Ajouter un produit
        </button>
    </div>

            <?php
        $allowed_messages = ['Produit ajouté avec succès.', 'Produit modifié avec succès.', 'Produit supprimé avec succès.', 'Erreur lors de l\'ajout du produit.', 'Erreur lors de la modification.', 'Erreur lors de la suppression.'];

        if (isset($_SESSION['msg'])):
        ?>
        <div class="alert alert-<?= $_SESSION['type'] ?? 'info' ?> alert-dismissible fade show text-center" role="alert">
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
                    <th>Prix</th>
                    <th>Quantité</th>
                    <th>Seuil</th>
                    <th>Catégorie</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $i = 1; foreach ($produits as $prod): ?>
                    <tr>
                        <th><?= $i++ ?></th>
                        <td><?= htmlspecialchars($prod['nom']) ?></td>
                        <td><?= htmlspecialchars($prod['prix']) ?></td>
                        <td><?= htmlspecialchars($prod['quantite']) ?></td>
                        <td><?= htmlspecialchars($prod['seuil']) ?></td>
                        <td><?= htmlspecialchars($prod['categorie']) ?></td>
                        <td>
                            <a href="#" class="btn btn-dark btn-sm" data-bs-toggle="modal" data-bs-target="#editProduitModal<?= $prod['id'] ?>">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <a onclick="return confirm('Supprimer ce produit ?')" href="../models/public/delete-produit-post.php?id=<?= $prod['id'] ?>" class="btn btn-danger btn-sm">
                                <i class="bi bi-trash3-fill"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>

    <!-- MODALES DE MODIFICATION POUR CHAQUE PRODUIT -->
    <?php foreach ($produits as $prod): ?>
        <div class="modal fade" id="editProduitModal<?= $prod['id'] ?>" tabindex="-1" aria-labelledby="editProduitModalLabel<?= $prod['id'] ?>" aria-hidden="true">
            <div class="modal-dialog">
                <form action="../models/public/update-produit-post.php" method="post" class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Modifier produit</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" value="<?= $prod['id'] ?>">

                        <div class="mb-2">
                            <label class="form-label">Nom</label>
                            <input type="text" name="nom" value="<?= htmlspecialchars($prod['nom']) ?>" class="form-control" required>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Prix</label>
                            <input type="number" name="prix" step="0.01" value="<?= $prod['prix'] ?>" class="form-control" required>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Quantité</label>
                            <input type="number" name="quantite" value="<?= $prod['quantite'] ?>" class="form-control" required>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Seuil</label>
                            <input type="number" name="seuil" value="<?= $prod['seuil'] ?>" class="form-control">
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Catégorie</label>
                            <select name="id_categorie" class="form-control" required>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?= $cat['id'] ?>" <?= $cat['id'] == $prod['id_categorie'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($cat['nom']) ?>
                                    </option>
                                <?php endforeach ?>
                            </select>
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

<!-- 🔽 Modal d’ajout produit -->
<div class="modal fade" id="addProduitModal" tabindex="-1" aria-labelledby="addProduitModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="../models/public/add-produit-post.php" method="post" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nouveau produit</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <div class="mb-2">
                    <label class="form-label">Nom</label>
                    <input type="text" name="nom" class="form-control" required>
                </div>
                <div class="mb-2">
                    <label class="form-label">Prix</label>
                    <input type="number" name="prix" step="0.01" class="form-control" required>
                </div>
                <div class="mb-2">
                    <label class="form-label">Quantité</label>
                    <input type="number" name="quantite" class="form-control" required>
                </div>
                <div class="mb-2">
                    <label class="form-label">Seuil</label>
                    <input type="number" name="seuil" class="form-control">
                </div>
                <div class="mb-2">
                    <label class="form-label">Catégorie</label>
                    <select name="id_categorie" class="form-control" required>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['nom']) ?></option>
                        <?php endforeach ?>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-dark">Ajouter</button>
            </div>
        </form>
    </div>
</div>

<?php require_once('script.php'); ?>
