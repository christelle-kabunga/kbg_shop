<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<?php require_once('style.php')?>

<style>
body {
    background: red;
    min-height: 100vh;
    background-image: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.4)), url("../assets/image/404.png");
}
h1{
    font-size: 4rem;
}
</style>
<body class="d-flex justify-content-center align-items-center">
    <div class="col-xl-4 col-lg-5 col-sm-6 col-md-6 p-4 text-white">
        <h1><b>404</b></h1>

        <div class="text-center">
            <span class="mb-5"><b>L'URL demandée n'a pas été trouvée sur cette application .</b></span>
            <h6 class="mt-3">Veuillez vous connecter à votre compte, ou assurez-vous d’avoir entré la bonne adresse dans votre barre d’adresse. Il se peut que vous ne disposiez pas des autorisations nécessaires pour accéder à ce système.</h6>

            <a href="../index.php" class="btn btn-outline-light w-50 mt-3 p-2">Retour </a>
        </div>
    </div>
</body>

</html>