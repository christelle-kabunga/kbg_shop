<?php
session_start();
require_once('../connexion/connexion.php');
require_once('../models/controllers/VenteController.php');
require_once('../models/controllers/ProduitController.php');

$vCtrl = new VenteController($connexion);
$pCtrl = new ProduitController($connexion);

$ventes   = $vCtrl->getVentes();
$produits = $pCtrl->getProduits();
?>

<?php require_once('style.php'); ?>
<?php require_once('aside.php'); ?>

<main id="main" class="main">
  <div class="d-flex justify-content-between align-items-center mb-4">
      <h4>Ventes</h4>
      <button class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#addVenteModal">
         Nouvelle vente
      </button>
  </div>

  <?php if(isset($_SESSION['msg'])): ?>
    <div class="alert alert-<?= $_SESSION['type'] ?> text-center">
        <?= $_SESSION['msg']; unset($_SESSION['msg'],$_SESSION['type']); ?>
    </div>
  <?php endif; ?>

  <div class="table-responsive card p-3">
    <table class="table table-borderless datatable">
      <thead>
        <tr>
          <th>#</th><th>Date</th><th>Produit</th><th>Qté</th>
          <th>Prix U</th><th>Total</th><th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php $i=1; foreach ($ventes as $v): ?>
        <tr>
          <td><?= $i++ ?></td>
          <td><?= $v['date_vente'] ?></td>
          <td><?= htmlspecialchars($v['produit']) ?></td>
          <td><?= $v['quantite'] ?></td>
          <td><?= number_format($v['prix_unitaire'],2,',',' ') ?></td>
          <td><?= number_format($v['total'],2,',',' ') ?></td>
          <td class="text-nowrap">
          <a  class="btn btn-dark btn-sm"
                data-bs-toggle="modal"
                data-bs-target="#editVenteModal"
                data-id="<?= $v['id'] ?>"
                data-produit="<?= $v['produit_id'] ?>"
                data-produit-nom="<?= htmlspecialchars($v['produit']) ?>"
                data-quantite="<?= $v['quantite'] ?>"
                data-prix="<?= $v['prix_unitaire'] ?>">
                <i class="bi bi-pencil-square"></i>
            </a>
            <a onclick="return confirm('Masquer cette vente ?')"
               href="../models/public/delete-vente.php?id=<?= $v['id'] ?>"
               class="btn btn-danger btn-sm">
               <i class="bi bi-trash3-fill"></i>
            </a>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</main>

<!-- Modale d'ajout -->
<div class="modal fade" id="addVenteModal" tabindex="-1">
  <div class="modal-dialog">
    <form action="../models/public/add-vente.php" method="post" class="modal-content">
      <div class="modal-header"><h5 class="modal-title">Nouvelle vente</h5>
        <button class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-2">
          <label>Produit</label>
          <select name="id_produit" class="form-control" required>
            <?php foreach ($produits as $p): ?>
              <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['nom']) ?></option>
            <?php endforeach ?>
          </select>
        </div>
        <div class="mb-2">
          <label>Quantité</label>
          <input type="number" name="quantite" class="form-control" required>
        </div>
        <div class="mb-2">
          <label>Prix unitaire</label>
          <input type="number" step="0.01" name="prix_unitaire" class="form-control" required>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-dark">Enregistrer</button>
      </div>
    </form>
  </div>
</div>
<!-- 🔽 MODALE MODIFIER VENTE -->
<div class="modal fade" id="editVenteModal" tabindex="-1">
  <div class="modal-dialog">
    <form action="../models/public/update-vente.php" method="post" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Modifier la vente</h5>
        <button class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="id" id="editId">
        <input type="hidden" name="ancien_quantite" id="ancienQte"> <!-- pour ajuster stock -->
        
        <div class="mb-2">
          <label>Produit</label>
          <input class="form-control" id="editProduitNom" disabled>
          <input type="hidden" name="id_produit" id="editProduitId">
        </div>
        <div class="mb-2">
          <label>Quantité</label>
          <input type="number" name="quantite" id="editQuantite" class="form-control" required>
        </div>
        <div class="mb-2">
          <label>Prix unitaire</label>
          <input type="number" step="0.01" name="prix_unitaire" id="editPrix" class="form-control" required>
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
document.addEventListener('DOMContentLoaded',()=>{
  const modal = document.getElementById('editVenteModal');
  modal.addEventListener('show.bs.modal', e=>{
    const btn = e.relatedTarget;
    document.getElementById('editId').value           = btn.dataset.id;
    document.getElementById('editProduitId').value    = btn.dataset.produit;
    document.getElementById('editProduitNom').value   = btn.dataset.produitNom;
    document.getElementById('editQuantite').value     = btn.dataset.quantite;
    document.getElementById('ancienQte').value        = btn.dataset.quantite;
    document.getElementById('editPrix').value         = btn.dataset.prix;
  });
});
</script>
