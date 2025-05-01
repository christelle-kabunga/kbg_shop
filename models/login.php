<?php
session_start();
require_once('../connexion/connexion.php');   // $connexion = new PDO(...)

if (isset($_POST['username'], $_POST['password'])) {
    $email    = trim($_POST['username']);
    $password = $_POST['password'];

    $sql  = "SELECT * FROM utilisateur WHERE email = ?";
    $stmt = $connexion->prepare($sql);
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Vérifier l’e‑mail et le mot de passe
    if ($user && $password === $user['mot_de_passe']) {

        // 1) poser l’ID et d’autres infos dans la session
        $_SESSION['user_id']  = $user['id'];
        $_SESSION['noms']     = $user['noms'];
        $_SESSION['fonction'] = $user['fonction'];

        // 2) rediriger vers la page d’accueil (ou stock)
        header('Location: ../views/index.php');
        exit;

    } else {
        $_SESSION['msg'] = "Identifiants incorrects.";
         header("location:../index.php");
        exit;
    }
}
 header("location:../index.php");
exit;
