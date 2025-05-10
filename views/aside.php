<?php
    include '../connexion/connexion.php';
    // if (!isset($_SESSION['iduser']) || empty($_SESSION['iduser'])) {
    //     header("location:ops.php");
    //     exit();
    // }
?>

<header id="header" class="header fixed-top d-flex align-items-center">
    <div class="d-flex align-items-center justify-content-between">
        <a href="index.php" class="logo d-flex align-items-center">
            <span class="d-none d-lg-block">Kbg Shop</span>
        </a>
        <i class="bi bi-list toggle-sidebar-btn"></i>
    </div>

    <div class="search-bar">
        <form class="search-form d-flex align-items-center" method="get">
            <input type="text" name="search" placeholder="Recherche..." title="Entrez un mot-clé" autocomplete="off">
            <button type="submit" title="Search"><i class="bi bi-search"></i></button>
        </form>
    </div>

    <nav class="header-nav ms-auto">
        <ul class="d-flex align-items-center">
            <li class="nav-item d-block d-lg-none">
                <a class="nav-link nav-icon search-bar-toggle" href="#">
                    <i class="bi bi-search"></i>
                </a>
            </li>
            <li class="nav-item dropdown pe-3">
                <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
                    <img src="<?php echo isset($_SESSION['image']) ? '../assets/img/profiles/' . $_SESSION['image'] : ''; ?>" width="35" height="35" class="rounded-circle" alt="Profile">
                    <span class="d-none d-md-block dropdown-toggle ps-2"><?php echo $_SESSION['noms']; ?></span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                    <li class="dropdown-header">
                        <h6><?php echo $_SESSION['noms']; ?></h6>
                        <span><?php echo $_SESSION['fonction'] === "admin" ? "Administrateur" : "Vendeur"; ?></span>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item d-flex align-items-center" href="users-profile.php">
                            <i class="bi bi-person"></i>
                            <span>Mon Profil</span>
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item d-flex align-items-center" href="../models/log-out.php">
                            <i class="bi bi-box-arrow-right"></i>
                            <span>Déconnexion</span>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </nav>
</header>

<!-- ======= Sidebar ======= -->
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<aside id="sidebar" class="sidebar">
<ul class="sidebar-nav" id="sidebar-nav">
    <!-- Tableau de bord -->
    <li class="nav-item">
        <a class="nav-link text-dark" href="index.php">
            <i class="bi bi-house"></i><span>Accueil</span>
        </a>
        <a class="nav-link text-dark" href="dashbord.php">
            <i class="bi bi-grid"></i><span>Tableau de Bord</span>
        </a>
    </li>

    <!-- Visible uniquement pour ADMIN -->
    <?php if ($_SESSION['fonction'] === 'admin') : ?>
        <li class="nav-heading">Produits</li>
        <li class="nav-item"><a class="nav-link text-dark" href="categories.php"><i class="bi bi-tags"></i><span>Catégories</span></a></li>
        <li class="nav-item"><a class="nav-link text-dark" href="produits.php"><i class="bi bi-box-seam"></i><span>Produits</span></a></li>

        <li class="nav-heading">Stock</li>
        <li class="nav-item"><a class="nav-link text-dark" href="stock.php"><i class="bi bi-shop"></i><span>Stock Général</span></a></li>

        <li class="nav-heading">Finances</li>
        <li class="nav-item"><a class="nav-link text-dark" href="depense.php"><i class="bi bi-cash-stack"></i><span>Dépenses</span></a></li>
        <li class="nav-item"><a class="nav-link text-dark" href="recettes.php"><i class="bi bi-bar-chart-line"></i><span>Recettes</span></a></li>
        <li class="nav-item"><a class="nav-link text-dark" href="rapport-benefices.php"><i class="bi bi-pie-chart"></i><span>Rapport Bénéfices</span></a></li>

        <li class="nav-heading">Utilisateurs</li>
        <li class="nav-item"><a class="nav-link text-dark" href="utilisateurs.php"><i class="bi bi-people"></i><span>Utilisateurs</span></a></li>

        <li class="nav-heading">Logs</li>
        <li class="nav-item"><a class="nav-link text-dark" href="logs.php"><i class="bi bi-person"></i><span>Logs</span></a></li>
        <li class="nav-item"><a class="nav-link text-dark" href="logs_produit.php"><i class="bi bi-receipt"></i><span>Logs_produits</span></a></li>
        <li class="nav-item"><a class="nav-link text-dark" href="logs_vente.php"><i class="bi bi-receipt"></i><span>Logs_vente</span></a></li>
        <li class="nav-item"><a class="nav-link text-dark" href="logs_stock.php"><i class="bi bi-receipt"></i><span>Logs_stock</span></a></li>
        <li class="nav-item"><a class="nav-link text-dark" href="logs_depense.php"><i class="bi bi-receipt"></i><span>logs_depense</span></a></li>
        

        <li class="nav-heading">Dettes</li>
        <li class="nav-item"><a class="nav-link text-dark" href="dettes.php"><i class="bi bi-cash"></i><span>Liste des Dettes</span></a></li>
        <li class="nav-item"><a class="nav-link text-dark" href="paiement-dettes.php"><i class="bi bi-cash-coin"></i><span>Paiement Dettes</span></a></li>
    <?php endif; ?>

    <!-- Visible uniquement pour VENDEUR -->
    <?php if ($_SESSION['fonction'] === 'vendeur') : ?>
        <li class="nav-heading">Ventes</li>
        <li class="nav-item"><a class="nav-link text-dark" href="ventes.php"><i class="bi bi-basket"></i><span>Ventes</span></a></li>
        <li class="nav-item"><a class="nav-link text-dark" href="historique-ventes.php"><i class="bi bi-clock-history"></i><span>Historique Ventes</span></a></li>
        <li class="nav-item"><a class="nav-link text-dark" href="ticket.php"><i class="bi bi-receipt"></i><span>Tickets de Vente</span></a></li>

        <li class="nav-heading">Clients</li>
        <li class="nav-item"><a class="nav-link text-dark" href="client.php"><i class="bi bi-person"></i><span>Clients</span></a></li>
    <?php endif; ?>
</ul>

</aside>
