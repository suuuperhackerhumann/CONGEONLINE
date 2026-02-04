<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include "db.php";

if(!isset($_SESSION['employe_id'])){
    header("Location: index.php");
    exit();
}

$stmt = $pdo->prepare("
    SELECT d.id, t.libelle, d.date_debut, d.date_fin, d.statut, d.motif
    FROM demande d
    JOIN type_demande t ON d.id_type = t.id
    WHERE d.id_employe = ?
    ORDER BY d.date_demande DESC
");
$stmt->execute([$_SESSION['employe_id']]);
$demandes = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mes demandes</title>
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
            <div class="company-name">GESTION DE CONGÉS</div>
            <div id="sitename">APPLICATION WEB</div>
        </div>
        <div class="personal-logo">
            <img src="../images/pl.png" alt="Logo Personnel">
        </div>
    </div>

    <h2>Mes demandes</h2>

    <!-- Bouton Nouvelle demande -->
    <div class="action-buttons">
        <a href="demande.php" class="btn">➕ Nouvelle demande</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>Type</th>
                <th>Du</th>
                <th>Au</th>
                <th>Motif</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody>
            <?php if(!empty($demandes)): ?>
                <?php foreach($demandes as $d): ?>
                    <tr>
                        <td><?= htmlspecialchars($d['libelle']) ?></td>
                        <td><?= htmlspecialchars($d['date_debut']) ?></td>
                        <td><?= htmlspecialchars($d['date_fin']) ?></td>
                        <td><?= htmlspecialchars($d['motif']) ?></td>
                        <td>
                            <span class="statut statut-<?= strtolower(str_replace(' ', '-', $d['statut'])) ?>">
                                <?= htmlspecialchars($d['statut']) ?>
                            </span>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="5">Aucune demande enregistrée.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="links">
        <a href="logout.php">Déconnexion</a>
    </div>
</div>
</body>
</html>
