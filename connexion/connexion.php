<?php
// Démarrer la session s'il n'y en a pas déjà une
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

try {
    $connexion = new PDO('mysql:host=localhost;dbname=kbg_shop;charset=utf8', 'root', '');
    $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}
