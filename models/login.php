<?php
session_start();
require_once('../connexion/connexion.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['username']);
    $password = $_POST['password'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || empty($password)) {
        $_SESSION['msg'] = "Données invalides.";
        header("location:../index.php");
        exit;
    }

    $stmt = $connexion->prepare("SELECT * FROM utilisateur WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['mot_de_passe'])) {
        $_SESSION['user_id']  = $user['id'];
        $_SESSION['noms']     = $user['noms'];
        $_SESSION['fonction'] = $user['role'];
        $_SESSION['image']    = $user['image'] ?? 'default.png';

        header("location:../views/index.php");
        exit;
    } else {
        $_SESSION['msg'] = "Identifiants incorrects.";
        header("location:../index.php");
        exit;
    }
}
header("location:../index.php");
exit;
