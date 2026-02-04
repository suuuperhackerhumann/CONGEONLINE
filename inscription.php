<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);


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

if (isset($_POST['register'])) {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $poste = $_POST['poste'];
    $service = $_POST['service'];

    try {
        $check = $pdo->prepare("SELECT * FROM employe WHERE email = ?");
        $check->execute([$email]);

        if ($check->rowCount() > 0) {
            $error = "Cet email existe déjà.";
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO employe (nom, prenom, email, password, poste, service) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$nom, $prenom, $email, $hashed, $poste, $service]);
            $success = "Compte créé avec succès !";
        }
    } catch (PDOException $e) {
        $error = "Erreur SQL : " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
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
    
        <h2>Créer un compte employé</h2>

        <?php if (isset($error)): ?>
            <div class="message error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if (isset($success)): ?>
            <div class="message success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <form method="post">
            <div class="form-group">
                <label>Nom</label>
                <input type="text" name="nom" required>
            </div>

            <div class="form-group">
                <label>Prénom</label>
                <input type="text" name="prenom" required>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" required>
            </div>

            <div class="form-group">
                <label>Mot de passe</label>
                <input type="password" name="password" required>
            </div>

            <div class="form-group">
                <label>Poste</label>
                <input type="text" name="poste" required>
            </div>

            <div class="form-group">
                <label>Service</label>
                <input type="text" name="service" required>
            </div>

            <input type="submit" name="register" value="Créer un compte">
        </form>

        <div class="links">
            <a href="index.php">Déjà un compte ? Se connecter</a>
        </div>
    </div>
</body>
</html>
