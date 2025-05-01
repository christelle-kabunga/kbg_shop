<?php 
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

require_once('../connexion/connexion.php');
require_once('../models/controllers/StockController.php');
require_once('../models/controllers/ProduitController.php');

$stockController = new StockController($connexion);
$produitController = new ProduitController($connexion);

// Récupérer les mouvements de stock et les produits
$mouvements = $stockController->getMouvements();  // Utilisation de `getMouvements` pour récupérer les mouvements
$produits = $produitController->getProduits();
?>

<?php require_once('style.php'); ?>
<?php require_once('aside.php'); ?>

<main id="main" class="main">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>Mouvements de stock</h4>
        <button class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#addStockModal"> <!-- Modale pour ajouter un stock -->
            Ajouter un mouvement
        </button>
    </div>

    <?php if (isset($_SESSION['msg'])): ?>
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
                    <th>#</th>
                    <th>Date</th>
                    <th>Produit</th>
                    <th>Mouvement</th>
                    <th>Quantité</th>
                    <th>Actions</th> <!-- Ajout d'une colonne pour les actions -->
                </tr>
            </thead>
            <tbody>
                <?php $i = 1; foreach ($mouvements as $m): ?>
                    <tr>
                        <td><?= $i++ ?></td>
                        <td><?= htmlspecialchars($m['date_mouvement']) ?></td>
                        <td><?= htmlspecialchars($m['nom_produit']) ?></td>
                        <td>
                            <span class="badge bg-<?= $m['mouvement'] === 'entrée' ? 'success' : 'danger' ?>">
                                <?= ucfirst($m['mouvement']) ?>
                            </span>
                        </td>
                        <td><?= $m['quantite'] ?></td>
                        <td class="text-nowrap">


                                <a  href="#"
                                    class="btn btn-dark btn-sm"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editStockModal"
                                    data-id="<?= $m['id'] ?>"
                                    data-produit="<?= $m['produit_id'] ?>"
                                    data-mouvement="<?= $m['mouvement'] ?>"
                                    data-quantite="<?= $m['quantite'] ?>"
                                    title="Modifier">
                                    <i class="bi bi-pencil-square"></i>
                                </a>

                                <!-- Icône SUPPRIMER -->
                                <a  onclick="return confirm('Supprimer ce mouvement ?')"
                                    href="../models/public/delete-stock.php?id=<?= $m['id'] ?>"
                                    class="btn btn-danger btn-sm"
                                    title="Supprimer">
                                    <i class="bi bi-trash3-fill"></i>
                                </a>

                        </td>

                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
</main>

<!-- 🔽 MODALE AJOUT STOCK -->
<div class="modal fade" id="addStockModal" tabindex="-1" aria-labelledby="addStockModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="../models/public/add-stock.php" method="post" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ajouter un mouvement de stock</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <div class="mb-2">
                    <label class="form-label">Produit</label>
                    <select name="id_produit" class="form-control" required>
                        <?php foreach ($produits as $prod): ?>
                            <option value="<?= $prod['id'] ?>"><?= htmlspecialchars($prod['nom']) ?></option>
                        <?php endforeach ?>
                    </select>
                </div>
                <div class="mb-2">
                    <label class="form-label">Type de mouvement</label>
                    <select name="mouvement" class="form-control" required>
                        <option value="entrée">Entrée</option>
                        <option value="sortie">Sortie</option>
                    </select>
                </div>
                <div class="mb-2">
                    <label class="form-label">Quantité</label>
                    <input type="number" name="quantite" class="form-control" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-dark">Ajouter</button>
            </div>
        </form>
    </div>
</div>

<!-- 🔽 MODALE MODIFIER STOCK -->
<div class="modal fade" id="editStockModal" tabindex="-1" aria-labelledby="editStockModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="../models/public/edit-stock.php" method="post" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modifier un mouvement de stock</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="id" id="editId">
                <div class="mb-2">
                    <label class="form-label">Produit</label>
                    <select name="id_produit" id="editProduit" class="form-control" required>
                        <?php foreach ($produits as $prod): ?>
                            <option value="<?= $prod['id'] ?>"><?= htmlspecialchars($prod['nom']) ?></option>
                        <?php endforeach ?>
                    </select>
                </div>
                <div class="mb-2">
                    <label class="form-label">Type de mouvement</label>
                    <select name="mouvement" id="editMouvement" class="form-control" required>
                        <option value="entrée">Entrée</option>
                        <option value="sortie">Sortie</option>
                    </select>
                </div>
                <div class="mb-2">
                    <label class="form-label">Quantité</label>
                    <input type="number" name="quantite" id="editQuantite" class="form-control" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-dark">Modifier</button>
            </div>
        </form>
    </div>
</div>

<?php require_once('script.php'); ?>
<script>
    // Script pour remplir la modale de modification avec les données de l'élément sélectionné
document.addEventListener('DOMContentLoaded', function() {
    const editStockModal = document.getElementById('editStockModal');
    editStockModal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const id = button.getAttribute('data-id');
        const produitId = button.getAttribute('data-produit');
        const mouvement = button.getAttribute('data-mouvement');
        const quantite = button.getAttribute('data-quantite');
        
        document.getElementById('editId').value = id;
        document.getElementById('editProduit').value = produitId;
        document.getElementById('editMouvement').value = mouvement;
        document.getElementById('editQuantite').value = quantite;
    });
});
</script>