<?php
session_start();
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin'){
    header("Location: index.php");
    exit();
}
include "db.php";

// Valider ou refuser une demande
if(isset($_GET['action']) && isset($_GET['id'])){
    $id = $_GET['id'];
    $action = $_GET['action'];
    $comment = $_GET['comment'] ?? '';
    if($action == "valider"){
        $stmt = $pdo->prepare("UPDATE demande SET statut='Validé', commentaire_admin=? WHERE id=?");
        $stmt->execute([$comment, $id]);
        $success = "Demande validée avec succès !";
    } elseif($action == "refuser"){
        $stmt = $pdo->prepare("UPDATE demande SET statut='Refusé', commentaire_admin=? WHERE id=?");
        $stmt->execute([$comment, $id]);
        $success = "Demande refusée.";
    }
}

// Récupérer toutes les demandes
$stmt = $pdo->query("SELECT d.id, e.nom, e.prenom, t.libelle, d.date_debut, d.date_fin, d.statut, d.motif
                     FROM demande d
                     JOIN employe e ON d.id_employe = e.id
                     JOIN type_demande t ON d.id_type = t.id
                     ORDER BY d.date_demande DESC");
$demandes = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des demandes</title>
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
    
        <h2>Gestion des demandes</h2>
        
        <?php if(isset($success)): ?>
            <div class="message success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <table>
            <thead>
                <tr>
                    <th>Employé</th>
                    <th>Type</th>
                    <th>Du</th>
                    <th>Au</th>
                    <th>Motif</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($demandes as $d): ?>
                <tr>
                    <td><?= htmlspecialchars($d['nom'] . ' ' . $d['prenom']) ?></td>
                    <td><?= htmlspecialchars($d['libelle']) ?></td>
                    <td><?= htmlspecialchars($d['date_debut']) ?></td>
                    <td><?= htmlspecialchars($d['date_fin']) ?></td>
                    <td><?= htmlspecialchars($d['motif']) ?></td>
                    <td>
                        <span class="statut statut-<?= strtolower(str_replace(' ', '-', $d['statut'])) ?>">
                            <?= htmlspecialchars($d['statut']) ?>
                        </span>
                    </td>
                    <td>
                        <?php if($d['statut'] == 'En attente'): ?>
                        <div class="action-buttons">
                            <a href="demandes.php?action=valider&id=<?= $d['id'] ?>" class="btn-action btn-validate">
                                ✓ Valider
                            </a>
                            <a href="demandes.php?action=refuser&id=<?= $d['id'] ?>" class="btn-action btn-reject">
                                ✗ Refuser
                            </a>
                        </div>
                        <?php else: ?>
                        <span class="btn-action btn-disabled">Traité</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="links">
            <a href="dashboard.php">← Retour Dashboard</a>
            <a href="logout.php">Déconnexion</a>
        </div>
    </div>
</body>
</html>