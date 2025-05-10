<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Accueil</title>
    <meta content="" name="description">
    <meta content="" name="keywords">
    <?php require_once('style.php') ?>
    <style>
    main.body {
    background-image: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.4)), url(../assets/image/DK6.png);
    background-position: center;
    min-height: calc(100vh - 60px);
    background-size: cover;
    display: flex;
    align-items: center;
}
    </style>

</head>

<body>

    <?php require_once('aside.php') ?>

    <main id="main" class="main">

<div class="pagetitle">
  <h1>Dashboard</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="index.php">Accueil</a></li>
      <li class="breadcrumb-item active">Dashboard</li>
    </ol>
  </nav>
</div><!-- End Page Title -->

<!-- dashboard.php -->
<section class="section dashboard">
  <div class="row">

    <!-- Cartes principales -->
    <div class="row">

      <!-- Carte Produits -->
      <div class="col-xxl-3 col-md-6">
        <div class="card info-card sales-card">
          <div class="filter">
            <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
              <li><a class="dropdown-item filter-item" href="#" data-type="produits" data-filter="today">Aujourd'hui</a></li>
              <li><a class="dropdown-item filter-item" href="#" data-type="produits" data-filter="month">Ce mois</a></li>
              <li><a class="dropdown-item filter-item" href="#" data-type="produits" data-filter="year">Cette année</a></li>
              <li><a class="dropdown-item filter-item" href="#" data-type="produits" data-filter="all">Tous</a></li>
            </ul>
          </div>
          <div class="card-body">
            <h5 class="card-title">Produits <span id="produits-label">| Ce mois</span></h5>
            <div class="d-flex align-items-center">
              <div class="card-icon rounded-circle d-flex align-items-center justify-content-center ">
                <i class="bi bi-box"></i>
              </div>
              <div class="ps-3">
                <h6 id="produits-val">145</h6>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Carte Stock -->
      <div class="col-xxl-3 col-md-6">
        <div class="card info-card revenue-card">
          <div class="filter">
            <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
              <li><a class="dropdown-item filter-item" href="#" data-type="stock" data-filter="today">Aujourd'hui</a></li>
              <li><a class="dropdown-item filter-item" href="#" data-type="stock" data-filter="month">Ce mois</a></li>
              <li><a class="dropdown-item filter-item" href="#" data-type="stock" data-filter="year">Cette année</a></li>
              <li><a class="dropdown-item filter-item" href="#" data-type="stock" data-filter="all">Tous</a></li>
            </ul>
          </div>
          <div class="card-body">
            <h5 class="card-title">Stock <span id="stock-label">| Ce mois</span></h5>
            <div class="d-flex align-items-center">
              <div class="card-icon rounded-circle d-flex align-items-center justify-content-center ">
                <i class="bi bi-archive"></i>
              </div>
              <div class="ps-3">
                <h6 id="stock-val">5,264</h6>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Carte Ventes -->
      <div class="col-xxl-3 col-md-6">
        <div class="card info-card customers-card">
          <div class="filter">
            <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
              <li><a class="dropdown-item filter-item" href="#" data-type="ventes" data-filter="today">Aujourd'hui</a></li>
              <li><a class="dropdown-item filter-item" href="#" data-type="ventes" data-filter="month">Ce mois</a></li>
              <li><a class="dropdown-item filter-item" href="#" data-type="ventes" data-filter="year">Cette année</a></li>
              <li><a class="dropdown-item filter-item" href="#" data-type="ventes" data-filter="all">Tous</a></li>
            </ul>
          </div>
          <div class="card-body">
            <h5 class="card-title">Ventes <span id="ventes-label">| Ce mois</span></h5>
            <div class="d-flex align-items-center">
              <div class="card-icon rounded-circle d-flex align-items-center justify-content-center ">
                <i class="bi bi-cart"></i>
              </div>
              <div class="ps-3">
                <h6 id="ventes-val">3,264</h6>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Carte Dettes -->
      <div class="col-xxl-3 col-md-6">
        <div class="card info-card revenue-card">
          <div class="filter">
            <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
              <li><a class="dropdown-item filter-item" href="#" data-type="dettes" data-filter="today">Aujourd'hui</a></li>
              <li><a class="dropdown-item filter-item" href="#" data-type="dettes" data-filter="month">Ce mois</a></li>
              <li><a class="dropdown-item filter-item" href="#" data-type="dettes" data-filter="year">Cette année</a></li>
              <li><a class="dropdown-item filter-item" href="#" data-type="dettes" data-filter="all">Tous</a></li>
            </ul>
          </div>
          <div class="card-body">
            <h5 class="card-title">Dettes <span id="dettes-label">| Ce mois</span></h5>
            <div class="d-flex align-items-center">
              <div class="card-icon rounded-circle d-flex align-items-center justify-content-center ">
                <i class="bi bi-cash-coin"></i>
              </div>
              <div class="ps-3">
                <h6 id="dettes-val">1,264</h6>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div><!-- Fin des cartes -->
  </div>
</section>

<!-- SCRIPT JavaScript dynamique -->
<script>
  document.addEventListener("DOMContentLoaded", function () {
    const labels = {
      today: "Aujourd'hui",
      month: "Ce mois",
      year: "Cette année",
      all: "Tous"
    };

    // Fonction de chargement des données
    function chargerDonnees(type, filter) {
      const label = document.getElementById(type + "-label");
      if (label) label.textContent = "| " + (labels[filter] || "Tous");

      fetch(`get_data.php?type=${type}&filter=${filter}`)
        .then(response => response.text())
        .then(data => {
          const val = document.getElementById(type + "-val");
          if (val) val.textContent = data;
        })
        .catch(error => console.error('Erreur:', error));
    }

    // Ajout des écouteurs pour les clics manuels
    document.querySelectorAll(".filter-item").forEach(function (item) {
      item.addEventListener("click", function (e) {
        e.preventDefault();
        const type = this.getAttribute("data-type");
        const filter = this.getAttribute("data-filter");
        chargerDonnees(type, filter);
      });
    });

    // ➕ Chargement automatique des données "Aujourd'hui" pour chaque type au chargement
    ['produits', 'stock', 'ventes', 'dettes'].forEach(type => {
      chargerDonnees(type, 'today');
    });
  });
</script>



    <?php require_once('script.php') ?>
</body>
</html>
