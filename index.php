
<?php
include('connexion/connexion.php');
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<style>
body {
    min-height: 100vh;
}
</style>
<?php require_once('views/style.php')?>
<body class="d-flex justify-content-center align-items-center px-3">
    <div class="fixed-top container text-center pt-4">
        <span></span>
    </div>
    <form method="POST" action="models/login.php" class="col-xl-4 col-lg-5 col-sm-7 col-md-6 card p-4">
        <h5 class="title">Connexion</h5>
        <div class="row">
            <div class="col-12 mb-3">

                <label for="">Adresse e-mail</label>
                <input type="text" class="form-control" placeholder="Ex: example@gmail.com" name="username">
            </div>
            <div class="col-12 mb-3">
                <label for="">Mot de passe</label>
                <input type="password" class="form-control" placeholder="Ex: *****" name="password">
            </div>
            <?php if(isset($_SESSION['msg']) && $_SESSION['msg']!=""){?>
            <div class="col-12 ">

                <div class="alert alert-danger text-center"><?php  echo $_SESSION['msg'];?></div>
            </div>
            <?php } ?>
            <div class="col-12 mb-3">
                <input type="submit" class="form-control btn-dark btn" name="connect" value="Se connecter">
            </div>
            <div class="col-12 mb-3 d-flex justify-content-between">
                <label><input type="checkbox" class="form-check-input"> Se souvenir de moi </label>
                <a href="">Mot de passe oublé ?</a>
            </div>
        </div>
    </form>
    <div class="fixed-bottom container text-center pb-4">
        <span>Droit réservé</span>
    </div>
</body>

</html>

