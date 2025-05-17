<?php
session_start();
require_once('../connexion/connexion.php');
require_once('../models/controllers/PaiementController.php');
require_once('../models/classes/PaiementDette.php');

$paiementController = new PaiementController($connexion);

// Récupérer les dettes
$dettes = $connexion->query("SELECT * FROM dette WHERE montant_restant > 0")->fetchAll(PDO::FETCH_ASSOC);

// Liste des paiements
$paiements = $paiementController->getPaiements();
?>

<?php require_once('style.php'); ?>
<?php require_once('aside.php'); ?>

<main id="main" class="main">
    <div class="card p-3">
        <h4 class="mb-3">Paiement des dettes</h4>

        <?php if (isset($_SESSION['msg'])): ?>
            <div class="alert alert-<?= $_SESSION['type'] ?>">
                <?= $_SESSION['msg']; unset($_SESSION['msg'], $_SESSION['type']); ?>
            </div>
        <?php endif; ?>

        <div class="mb-3 text-end">
            <button class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#modalAddPaiement">
                Ajouter un paiement
            </button>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered datatable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Client</th>
                        <th>Montant payé</th>
                        <th>Date</th>
                        <th>Utilisateur</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; foreach ($paiements as $p): ?>
                        <tr>
                            <td><?= $i++ ?></td>
                            <td><?= htmlspecialchars($p['client']) ?></td>
                            <td><?= $p['montant_paye'] ?></td>
                            <td><?= $p['date_paiement'] ?></td>
                            <td><?= $p['utilisateur'] ?></td>
                            <td>
                                <a href="#" class="btn btn-dark btn-sm" data-bs-toggle="modal" data-bs-target="#modalEdit<?= $p['id'] ?>">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <a href="../models/public/delete-paiement-dette.php?id=<?= $p['id'] ?>" 
                                class="btn btn-danger btn-sm" 
                                onclick="return confirm('Confirmer la suppression de ce paiement ?')">
                                    <i class="bi bi-trash"></i>
                                </a>
                                <a href="#" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#modalDetail<?= $p['id'] ?>">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>

                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<!-- Modal Ajouter Paiement -->
<div class="modal fade" id="modalAddPaiement" tabindex="-1">
    <div class="modal-dialog">
        <form  action="../models/public/add-paiement-dette-post.php" method="post" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ajouter un paiement</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body row g-3">
                <div class="col-12">
                    <label>Dette</label>
                    <select name="dette_id" class="form-select" required>
                        <option value="">Choisir une dette</option>
                        <?php foreach ($dettes as $dette): ?>
                            <option value="<?= $dette['id'] ?>">
                                <?= htmlspecialchars($dette['client']) ?> (<?= $dette['montant'] ?>)
                            </option>
                        <?php endforeach ?>
                    </select>
                </div>
                <div class="col-12">
                    <label>Montant payé</label>
                    <input type="number" step="0.01" name="montant_paye" class="form-control" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-dark">Enregistrer</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
            </div>
        </form>
    </div>
</div>

<!-- Modals Modifier -->
<?php foreach ($paiements as $p): ?>
<div class="modal fade" id="modalEdit<?= $p['id'] ?>" tabindex="-1">
    <div class="modal-dialog">
        <form action="../models/public/update-paiement-dette.php" method="post" class="modal-content">
            <input type="hidden" name="id" value="<?= $p['id'] ?>">
            <div class="modal-header">
                <h5 class="modal-title">Modifier le paiement</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body row g-3">
                <div class="col-12">
                    <label>Montant payé</label>
                    <input type="number" step="0.01" name="montant_paye" class="form-control" value="<?= $p['montant_paye'] ?>" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-dark">Mettre à jour</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
            </div>
        </form>
    </div>
</div>
<?php endforeach; ?>

<?php foreach ($paiements as $p): ?>
<div class="modal fade" id="modalDetail<?= $p['id'] ?>" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Détails du paiement</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p><strong>Client :</strong> <?= htmlspecialchars($p['client']) ?></p>
                <p><strong>Montant de la dette :</strong> <?= number_format($p['montant_dette'], 0, ',', ' ') ?> FC</p>
                <p><strong>Montant payé :</strong> <?= number_format($p['montant_paye'], 0, ',', ' ') ?> FC</p>
                <p><strong>Montant restant :</strong> 
            <?= isset($p['montant_restant']) ? number_format($p['montant_restant'], 0, ',', ' ') . ' FC' : 'Non défini' ?></p>
                <p><strong>Date du paiement :</strong> <?= $p['date_paiement'] ?></p>
                <p><strong>Utilisateur :</strong> <?= htmlspecialchars($p['utilisateur']) ?></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>
<?php endforeach; ?>

<?php require_once('script.php'); ?>
