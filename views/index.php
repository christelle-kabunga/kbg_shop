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

    <main class="main body" id="main">
        <div class="container col-xl-12 col-lg-6 col-md-4 col-sm-12 mx-auto text-center">
            <h1 class="text-white mt-5 pt-5 h1"><b>Boutique Kbg Shop</b></h1>
            <h1 class="mx-auto text-white text-center">Bienvenue sur la plateforme de gestion de stock de Kbg Shop</h1>
            <p class="text-white text-center">
                Gérez facilement vos produits, suivez vos ventes, contrôlez vos stocks et améliorez votre activité commerciale en temps réel.<br>
                Cette application est conçue pour vous offrir une expérience fluide, rapide et efficace dans la gestion quotidienne de votre boutique.<br>
                Kbg Shop, c’est la simplicité au service de votre performance.
            </p>
            <a href="produits.php" class="btn btn-outline-success btn-lg mt-3 p-3">
                <b>Voir plus sur ce site</b> <i class="bi bi-arrow-right"></i>
            </a>
        </div>

    </main>
    <?php require_once('script.php') ?>
</body>
</html>
