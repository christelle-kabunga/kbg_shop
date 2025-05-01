<?php
include '../connexion/connexion.php';//Se connecter à la BD
#Appel de la page qui fait les affichages
require_once ('../models/select/select-utilisateur.php');
#appel de la fontion
require_once ('../fonctions/fonctions.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Utilisateur</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <?php require_once ('style.php'); ?>

</head>

<body>

    <!-- Appel de menues  -->
    <?php require_once ('aside.php') ?>

    <main id="main" class="main">
        <div class="row">
            <div class="col-12">
                <h4><?= $title ?></h4>
            </div>
            <!-- pour afficher les massage  -->
            <?php if (isset($_SESSION['msg']) && !empty($_SESSION['msg'])) {
                ?>
                <div class="alert-info alert text-center"><?= $_SESSION['msg']; ?> </div><?php
            }
            unset($_SESSION['msg']);
            ?>
            <div class="col-xl-12 ">
                <form action="<?= $url ?>" method="POST" class="card p-3" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-xl-4 col-lg-4 col-md-4  col-sm-6 p-3">
                            <label for="">Nom <span class="text-danger">*</span></label>
                            <input autocomplete="off" name="nom" <?php if (isset($_GET['iduser'])) { ?>
                                    value="<?php echo $tab['nom']; ?>" <?php } else if (isset($_SESSION['recupnom']) || isset($_GET['idRecupUser'])) { ?>
                                        value="<?php echo $_SESSION['recupnom']; ?> <?php } unset($_SESSION['recupnom']); ?>" required type="text"
                                class="form-control" placeholder="EX: MUHINDO">
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4  col-sm-6 p-3">
                            <label for="">Postnom <span class="text-danger">*</span></label>
                            <input autocomplete="off" <?php if (isset($_GET['iduser'])) { ?>
                                    value="<?php echo $tab['postnom']; ?>" <?php } else if (isset($_SESSION['recuppost']) || isset($_GET['idRecupUser'])) { ?>
                                        value="<?php echo $_SESSION['recuppost']; ?> <?php } unset($_SESSION['recuppost']);?>" name="postnom" required type="text"
                                class="form-control" placeholder="EX: RAFIKI">
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4  col-sm-6 p-3">
                            <label for="">Prenom <span class="text-danger">*</span></label>
                            <input autocomplete="off" <?php if (isset($_GET['iduser'])) { ?>
                                    value="<?php echo $tab['prenom']; ?>" <?php } else if (isset($_SESSION['recupprenom']) || isset($_GET['idRecupUser'])) { ?>
                                        value="<?php echo $_SESSION['recupprenom']; ?> <?php } unset($_SESSION['recupprenom']);?>" name="prenom" required type="text"
                                class="form-control" placeholder="EX: DAVID">
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4  col-sm-6 p-3">
                            <label for="">Genre <span class="text-danger">*</span></label>
                            <select required name="genre" id="" class="form-select">
                                <?php $genre="";
                                if(isset($_SESSION['recupgenre']) || isset($_GET['idRecupUser']))
                                {
                                    $genre=$_SESSION['recupgenre'];
                                    ?>
                                    <option value="Masculin">Masculin</option>
                                    <option <?php if($genre=="Feminin"){?> Selected <?php } ?>value="Feminin" >Feminin</option> 
                                    <?php
                                    unset($_SESSION['recupgenre']);
                                }else{
                                    if(isset($_GET['iduser'])){
                                        $Mgenre=$tab['genre'];
                                     ?>
                                    <option value="Masculin">Masculin</option>
                                    <option <?php if($Mgenre=="Feminin"){?> Selected <?php } ?>value="Feminin" >Feminin</option> 
                                    <?php   
                                    }else{
                                        ?>
                                    <option value="Masculin">Masculin</option>
                                    <option value="Feminin" >Feminin</option> 
                                    <?php  
                                    }
                                    
                                }
                                ?>
                                
                            </select>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4  col-sm-6 p-3">
                            <label for="">Adresse <span class="text-danger">*</span></label>
                            <input autocomplete="off" <?php if (isset($_GET['iduser'])) { ?>
                                    value="<?php echo $tab['adresse']; ?>" <?php } else if (isset($_SESSION['recupadresse'])|| isset($_GET['idRecupUser'])) { ?>
                                        value="<?php echo $_SESSION['recupadresse']; ?> <?php } unset($_SESSION['recupadresse']); ?>" name="adresse" required
                                type="text" class="form-control" placeholder="EX: Bouembo, Q. Kitatumba, N° 16">
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4  col-sm-6 p-3">
                            <label for="">Telephone <span class="text-danger">*</span></label>
                            <input autocomplete="off" <?php if (isset($_GET['iduser'])) { ?>
                                    value="<?php echo $tab['telephone']; ?>" <?php } else if (isset($_SESSION['recuptel']) || isset($_GET['idRecupUser'])) { ?>
                                        value="<?php echo $_SESSION['recuptel']; ?> <?php } unset($_SESSION['recuptel']);?>" name="telephone" required type="text"
                                class="form-control" placeholder="EX: 0000000000">
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4  col-sm-6 p-3">
                            <label for="">Mail <span class="text-danger">*</span></label>
                            <input autocomplete="off" <?php if (isset($_GET['iduser'])) { ?>
                                    value="<?php echo $tab['email']; ?>" <?php } else if (isset($_SESSION['recupmail']) || isset($_GET['idRecupUser'])) { ?>
                                        value="<?php echo $_SESSION['recupmail']; ?> <?php } unset($_SESSION['recupmail']);?>" name="email" required type="email"
                                class="form-control" placeholder="EX: exemple@gmail.com">
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4  col-sm-6 p-3">
                            <label for="">Password <span class="text-danger">*</span></label>
                            <input autocomplete="off" <?php if (isset($_GET['iduser'])) { ?>
                                    value="<?php echo $tab['pwd']; ?>" <?php } else if (isset($_SESSION['recuppwd']) || isset($_GET['idRecupUser'])) { ?>
                                        value="<?php echo $_SESSION['recuppwd']; ?> <?php } unset($_SESSION['recuppwd']);?>" name="pwd" required type="text"
                                class="form-control" placeholder="EX: ********">
                        </div>

                        <div class="col-xl-4 col-lg-4 col-md-4  col-sm-6 p-3">
                            <label for="">Boutiques <span class="text-danger">*</span></label>
                      
                         
                                <select required name="boutique" id="" class="form-select" >
                                    <?php

                                    while ($databout = $getBoutique->fetch()){
                                        $bout=0;
                                        if(isset($_GET['recupBout']) || isset($_GET['idRecupUser'])) 
                                        {
                                            $bout=$_SESSION['recupBout'];
                                        }
                                        unset($_SESSION['recupBout']);
                                        if(isset($_GET['iduser']))
                                        {
                                            $bout=$tab['boutique'];
                                        }
                                        if($bout>0){
                                        ?>
                                        <option <?php if($bout==$databout['id']){ ?>Selected <?php }?> value="<?= $databout['id']; ?>">
                                            <?= $databout['nom'] . "  " . $databout['description']; ?>
                                        </option>
                                     <?php }else{ ?>
                                        <option value="<?= $databout['id']; ?>">
                                            <?= $databout['nom'] . "  " . $databout['description']; ?>
                                        </option> <?php
                                      } }
                                    ?>
                                </select>
                            

                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4  col-sm-6 p-3">
                            <label for="">Fonction <span class="text-danger">*</span></label>
                            <select name="fonction" id="" class="form-select">
                                <option value="admin">Admin</option>
                                <option value="comptable">Comptable</option>
                                <option value="vendeur">Vendeur</option>
                            </select>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4  col-sm-6 p-3">
                            <label for="">Photo <span class="text-danger">*</span></label>
                            <input autocomplete="off"  value="" name="photo" class="img-fluid" required type="file" class="form-control" placeholder="Aucun fichier">
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6  col-sm-6 ">
                            <input name="valider" type="submit" class="btn btn-dark w-100" value="<?= $btn ?>">
                        </div>
                    </div>
                </form>
            </div>
            <!-- La table qui affiche les données  -->
            <div class="col-xl-12 table-responsive px-3 card mt-4 px-4 pt-3">
                <table class="table table-borderless datatable ">
                    <thead>
                        <tr>
                            <th>N°</th>
                            <th>Nom</th>
                            <th>Postnom</th>
                            <th>prenom</th>
                            <th>Genre</th>
                            <th>Adresse</th>
                            <th>Tel</th>
                            <th>Mail</th>
                            <th>Boutiques</th>
                            <th>Fonction</th>
                            <th>Photo</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $n = 0;
                        while ($iduser = $getData->fetch()) {
                            $n++;
                            ?>
                            <tr>
                                <th scope="row"><?= $n; ?></th>
                                <td> <?= $iduser["nom"] ?></td>
                                <td> <?= $iduser["postnom"] ?></td>
                                <td> <?= $iduser["prenom"] ?></td>
                                <td> <?= $iduser["genre"] ?></td>
                                <td> <?= $iduser["adresse"] ?></td>
                                <td> <?= $iduser["telephone"] ?></td>
                                <td> <?= $iduser["email"] ?></td>
                                <td> <?= $iduser['boutique'] . "  " . $iduser['description'] ?></td>
                                <td> <?= $iduser["fonction"] ?></td>
                                <td> <img src="../assets/img/profiles/<?= $iduser["photo"] ?>" width='50' height="50"
                                        style="object-fit: cover;"></td>
                                <td><a href='utilisateur.php?iduser=<?= $iduser['id'] ?>' class="btn btn-dark btn-sm "><i
                                            class="bi bi-pencil-square"></i></a>
                                    <a onclick=" return confirm('Voulez-vous vraiment supprimer ?')"
                                        href='../models/delete/del-utilisateur-post.php?idSupcat=<?= $iduser['id'] ?>'
                                        class="btn btn-danger btn-sm "><i class="bi bi-trash3-fill"></i></a>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main><!-- End #main -->
    <?php require_once ('script.php') ?>

</body>

</html>