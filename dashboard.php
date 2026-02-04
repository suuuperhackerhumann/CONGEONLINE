<?php
session_start();
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin'){
    header("Location: index.php");
    exit();
}
include "db.php";

// Statistiques simples
$total_demandes = $pdo->query("SELECT COUNT(*) FROM demande")->fetchColumn();
$en_attente = $pdo->query("SELECT COUNT(*) FROM demande WHERE statut='En attente'")->fetchColumn();
$validees = $pdo->query("SELECT COUNT(*) FROM demande WHERE statut='Validé'")->fetchColumn();
$refusees = $pdo->query("SELECT COUNT(*) FROM demande WHERE statut='Refusé'")->fetchColumn();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <link rel="icon" href="../images/pl.png" type="image/png">

    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
     <div class="container">
        <div class="header-logos">
            <div class="logo-container">
                <img src="../images/logo_novasys.png" alt="Logo Entreprise">
            </div>
            
            <div class="logo-text">
                <div class="company-name">GESTION DE CONGÉS </div>
                <div id="sitename"> APPLICATION WEB </div>
            </div>
            
            <div class="personal-logo">
                <img src="../images/pl.png" alt="Logo Personnel">
            </div>
        </div>
        
        <h2>Dashboard Administrateur</h2>
        
        <div class="welcome">
            <h3>Bonjour, <?= htmlspecialchars($_SESSION['nom']) ?> 👋</h3>
            <p>Bienvenue sur votre espace d'administration</p>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <h3>Total demandes</h3>
                <div class="stat-number"><?= $total_demandes ?></div>
            </div>
            
            <div class="stat-card pending">
                <h3>En attente</h3>
                <div class="stat-number"><?= $en_attente ?></div>
            </div>
            
            <div class="stat-card approved">
                <h3>Validées</h3>
                <div class="stat-number"><?= $validees ?></div>
            </div>
            
            <div class="stat-card rejected">
                <h3>Refusées</h3>
                <div class="stat-number"><?= $refusees ?></div>
            </div>
        </div>

        <div class="links">
            <a href="demandes.php" class="btn">📋 Gérer les demandes</a>
            <a href="export_page.php" class="btn btn-export">📊 Exporter en PDF</a>
            <a href="logout.php">Déconnexion</a>
        </div>
    </div>
</body>
</html>