<?php
// === Connexion à la base de données ===

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

// --- Chargement des types de demande ---
$stmt = $pdo->query("SELECT id, libelle FROM type_demande ORDER BY libelle ASC");
$types = $stmt->fetchAll(PDO::FETCH_ASSOC);

// --- Traitement du formulaire ---
session_start();
$id_employe = $_SESSION['employe_id'] ?? 1; // à remplacer par la vraie session plus tard

if (isset($_POST['send'])) {
    $id_type = $_POST['type'];
    $date_debut = $_POST['date_debut'];
    $date_fin = $_POST['date_fin'];
    $motif = $_POST['motif'];

    if (!empty($id_type) && !empty($date_debut) && !empty($date_fin)) {
        $stmt = $pdo->prepare("INSERT INTO demande (id_employe, id_type, date_debut, date_fin, motif) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$id_employe, $id_type, $date_debut, $date_fin, $motif]);
        $success = "Votre demande a été enregistrée avec succès !";
    } else {
        $error = "Veuillez remplir tous les champs obligatoires.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faire une demande</title>
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
        <h2>Faire une demande</h2>

        <?php if (isset($success)): ?>
            <div class="message success"><?= $success ?></div>
        <?php elseif (isset($error)): ?>
            <div class="message error"><?= $error ?></div>
        <?php endif; ?>

        <form method="post">
            <div class="form-group">
                <label>Type de demande</label>
                <select name="type">
                    <?php foreach ($types as $t): ?>
                    <option value="<?= $t['id'] ?>"><?= htmlspecialchars($t['libelle']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Date de début</label>
                <input type="date" name="date_debut" required>
            </div>
            
            <div class="form-group">
                <label>Date de fin</label>
                <input type="date" name="date_fin" required>
            </div>
            
            <div class="form-group">
                <label>Motif</label>
                <textarea name="motif"></textarea>
            </div>

            <input type="submit" name="send" value="Envoyer la demande">
        </form>

        <div class="links">
            <a href="historique.php">Voir mes demandes</a>
            <a href="logout.php">Déconnexion</a>
        </div>
    </div>
</body>
</html>
