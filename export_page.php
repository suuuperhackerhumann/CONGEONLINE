<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exporter les demandes</title>
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
        <h2>📊 Exporter les demandes en PDF</h2>

        <div class="welcome">
            <h3>Options d'export</h3>
            <p>Personnalisez votre rapport en appliquant des filtres</p>
        </div>

        <form method="get" action="generer_pdf_recap.php">
            <div class="form-group">
                <label>Filtrer par statut</label>
                <select name="statut">
                    <option value="tous">Tous les statuts</option>
                    <option value="En attente">En attente</option>
                    <option value="Validé">Validé</option>
                    <option value="Refusé">Refusé</option>
                </select>
            </div>

            <div class="form-group">
                <label>Date de début (à partir de)</label>
                <input type="date" name="date_debut">
            </div>

            <div class="form-group">
                <label>Date de fin (jusqu'à)</label>
                <input type="date" name="date_fin">
            </div>

            <div style="display: flex; gap: 15px; margin-top: 30px;">
                <button type="submit" class="btn btn-export" style="flex: 1;">
                    📄 Générer le PDF
                </button>
                <a href="generer_pdf_recap.php" class="btn" style="flex: 1; text-align: center;">
                    📊 Tout exporter
                </a>
            </div>
        </form>

        <div class="links">
            <a href="dashboard.php">← Retour Dashboard</a>
            <a href="demandes.php">Gérer les demandes</a>
            <a href="logout.php">Déconnexion</a>
        </div>
    </div>
</body>
</html>
