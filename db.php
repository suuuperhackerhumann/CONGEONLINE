<?php

$host = "sql301.infinityfree.com";       // Nom d'hôte demandé
$dbname = "if0_40399885_gesconge";     // Nom de la base demandé
$user = "if0_40399885";             // Nom d'utilisateur demandé
$pass = "W5nYRM7st3w";  // Mot de passe demandé

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>
