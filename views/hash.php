<?php
$motDePasse = '1234';
$hash = password_hash($motDePasse, PASSWORD_DEFAULT);
echo "Mot de passe : $motDePasse<br>";
echo "Hash : $hash";
