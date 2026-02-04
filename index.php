<?php
session_start();
include "db.php";

if(isset($_POST['login'])){
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM employe WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if($user && password_verify($password, $user['password'])){
        $_SESSION['employe_id'] = $user['id'];
        $_SESSION['nom'] = $user['nom'];
        $_SESSION['prenom'] = $user['prenom'];
        $_SESSION['role'] = $user['role']; 

        if($user['role'] == 'admin'){
            header("Location: dashboard.php"); 
        } else {
            header("Location: historique.php");
        }
        exit;
    } else {
        $error = "Email ou mot de passe incorrect.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <link rel="icon" href="../images/x.png" type="image/png">

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
    
   
        <h2>Connexion</h2>
        <?php if(isset($error)): ?>
            <div class="message error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form method="post">
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" required>
            </div>
            <div class="form-group">
                <label>Mot de passe</label>
                <input type="password" name="password" required>
            </div>
            <input type="submit" name="login" value="Se connecter">

        </form>
        <div class="links">
            <a href="inscription.php">vous n'avez pas compte ? créez le</a>
        </div>
    </div>
</body>
</html>
