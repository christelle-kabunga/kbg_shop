    <?php
    #appel de la connexion a la base de données
    include_once('../connexion/connexion.php');
    #Appel de la page qui affiche les données
    include_once('../models/select/select-profil.php');
    ?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="utf-8">
        <meta content="width=device-width, initial-scale=1.0" name="viewport">

        <title>Profil</title>
        <meta content="" name="description">
        <meta content="" name="keywords">

        <?php require_once('style.php');?>

    </head>

    <body>

        <!-- Appel de menues  -->
        <?php require_once('aside.php') ?>

        <main id="main" class="main">

            <div class="pagetitle">
                <h1>Profile</h1>
            </div><!-- End Page Title -->

            <section class="section profile">
                <div class="row">
                    <div class="col-xl-4">

                        <div class="card">
                            <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">

                            <img src="<?php if (isset($_image) && ! empty($_image) ) {print '../assets/img/profiles/' . $_image;
                                                    } ?>" alt="Profile" class="rounded-circle">
                                <h2><?php print $_nom . " " . $_postnom; ?></h2>
                                <h3>Web Designer</h3>
                            </div>
                        </div>

                    </div>
                    

                    <div class="col-xl-8">
                        

                        <div class="card">
                            <div class="card-body pt-3">
                            <div class="col-xl-7 col-lg-7 col-md-6">
                    <?php
                        if(isset($_SESSION['msg']) && !empty($_SESSION['msg'])){
                            ?> <div class="alert alert-info text-center"><?= $_SESSION['msg'] ?> <?php   
                        }
                            unset($_SESSION['msg']);
                    ?>
                    </div>
                </div>
                                <!-- Bordered Tabs -->
                                <ul class="nav nav-tabs nav-tabs-bordered">

                                    <li class="nav-item">
                                        <button class="nav-link active " data-bs-toggle="tab" data-bs-target="#profile-overview">Votre profile</button>
                                    </li>

                                    <li class="nav-item">
                                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-edit">Modifier le profile</button>
                                    </li>

                                    <li class="nav-item">
                                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-change-password">Modifier le mot de passe</button>
                                    </li>

                                </ul>
                                <div class="tab-content pt-2">

                                    <div class="tab-content pt-2">

                                        <div class="tab-pane fade show active profile-overview" id="profile-overview">

                                            <h5 class="card-title px-5">Profile Details</h5>

                                            <div class="row">
                                                <div class="col-lg-3 col-md-4 label px-5 ">Nom</div>
                                                <div class="col-lg-9 col-md-8"><?php if(isset($_nom)){ print $_nom;}?></div>
                                            </div>

                                            <div class="row">
                                                <div class="col-lg-3 col-md-4 label px-5">Postnom

                                                </div>
                                                <div class="col-lg-9 col-md-8"><?php if(isset($_postnom)){ print $_postnom;}?></div>
                                            </div>

                                            <div class="row">
                                                <div class="col-lg-3 col-md-4 label px-5">Prenom</div>
                                                <div class="col-lg-9 col-md-8"><?php if(isset($_prenom)){ print $_prenom;}?></div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-3 col-md-4 label px-5">Genre</div>
                                                <div class="col-lg-9 col-md-8"><?php if(isset($_genre)){ print $_genre;}?></div>
                                            </div>


                                            <div class="row">
                                                <div class="col-lg-3 col-md-4 label px-5">Adresse</div>
                                                <div class="col-lg-9 col-md-8"><?php if(isset($_adresse)){ print $_adresse;}?></div>
                                            </div>

                                            <div class="row">
                                                <div class="col-lg-3 col-md-4 label px-5">Téléphone</div>
                                                <div class="col-lg-9 col-md-8"><?php if(isset($_telephone)){ print $_telephone;}?></div>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="tab-pane fade profile-edit pt-3" id="profile-edit">

                                        <!-- Profile Edit Form -->
                                        <form method="POST" enctype="multipart/form-data" action="../models/updat/up-profil-post.php?idMod=<?=$_SESSION['iduser'] ?>">
                                            <div class="row mb-3">
                                                <label for="profileImage" class="col-md-4 col-lg-3 col-form-label">Profile
                                                    Image</label>
                                                <div class="col-md-8 col-lg-9">
                                                    <img src="<?php if (isset($_image) && ! empty($_image) ) {print '../assets/img/profiles/'. $_image;
                                                    } ?>" alt="Profile">
                                                    <div class="pt-2">
                                                        <input autocomplete="off" name="photo" class="img-fluid"  type="file" class="form-control" >
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <label for="fullName" class="col-md-4 col-lg-3 col-form-label px-5">Nom</label>
                                                <div class="col-md-8 col-lg-9 ">
                                                    <input name="nom" type="text" class="form-control" id="fullName"  required value="<?php if (isset($_nom)) { print $_nom;} ?>">
                                                    
                                                                                                                        
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <label for="company" class="col-md-4 col-lg-3 col-form-label px-5">Postnom</label>
                                                <div class="col-md-8 col-lg-9">
                                                    <input name="postnom" type="text" class="form-control" id="company" required value="<?php if (isset($_postnom)) {print $_postnom;} ?>">
                                                                                                                                
                                                                                                                                    
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <label for="Job" class="col-md-4 col-lg-3 col-form-label px-5">Prenom</label>
                                                <div class="col-md-8 col-lg-9">
                                                    <input name="prenom" type="text" class="form-control" id="Job" required value="<?php if (isset($_prenom)) { print $_prenom;} ?>">
                                                                                                                        
                                                                                                                            
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <label for="Country" class="col-md-4 col-lg-3 col-form-label px-5">Genre</label>
                                                <div class="col-md-8 col-lg-9">
                                                    <input name="genre" type="text" class="form-control" id="Country" required value="<?php if (isset($_genre)) { print $_genre;}?>">
                                                                                                                                
                                                                                                                            
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <label for="Address" class="col-md-4 col-lg-3 col-form-label px-5">Addresse</label>
                                                <div class="col-md-8 col-lg-9">
                                                    <input name="adresse" type="text" class="form-control" id="Address" required value="<?php if (isset($_adresse)) {print $_adresse; }?>">
                                                                                                                                    
                                                                                                                            
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <label for="Phone" class="col-md-4 col-lg-3 col-form-label px-5">Téléphone</label>
                                                <div class="col-md-8 col-lg-9">
                                                    <input name="telephone" type="number" class="form-control" id="Phone" required value="<?php if (isset($_telephone)) {print $_telephone; } ?>">
                                                                                                                                        
                                                                                                                                
                                                </div>
                                            </div>

                                            <div class="text-center">
                                                <button type="submit" class="btn btn-dark" name="valider">
                                                    Modifier</button>
                                            </div>
                                        </form><!-- End Profile Edit Form -->

                                    </div>

                                    <div class="tab-pane fade pt-3" id="profile-change-password">
                                        <!-- Change Password Form -->
                                        <form method="POST" action="../models/updat/up-password.php">

                                            <div class="row mb-3 ">
                                                <label for="currentPassword"  class="col-md-6 col-lg-5 col-form-label px-5">Ancien
                                                    mot de passe</label>
                                                <div class="col-md-6 col-lg-7">
                                                    <input name="password" placeholder="Entrer votre ancien mot de passe" type="password" class="form-control" required id="currentPassword">
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <label for="newPassword" class="col-md-6 col-lg-5 col-form-label px-5">Nouveau
                                                    mot de passe</label>
                                                <div class="col-md-6 col-lg-7">
                                                    <input name="newpassword" placeholder="Entrer le nouveau mot de passe" type="password" required class="form-control" id="newPassword">
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <label for="renewPassword" class="col-md-6 col-lg-5 col-form-label px-5">confirmer le
                                                    nouveau mot de
                                                    passe</label>
                                                <div class="col-md-6 col-lg-7">
                                                    <input name="renewpassword" placeholder="Confirmer le nouveau mot de passe" type="password" class="form-control" required id="renewPassword">
                                                </div>
                                            </div>

                                            <div class="text-center">
                                                <button type="submit" name="valider" class="btn btn-dark">Modifier</button>
                                            </div>
                                        </form><!-- End Change Password Form -->

                                    </div>

                                </div><!-- End Bordered Tabs -->

                            </div>
                        </div>

                    </div>
                </div>
            </section>

        </main><!-- End #main -->
        <?php require_once('script.php') ?>

    </body>

    </html>