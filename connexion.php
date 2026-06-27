<?php
session_start();
require_once __DIR__ . '/config/db.php';

$erreur = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email        = trim($_POST['email'] ?? '');
    $mot_de_passe = $_POST['mot_de_passe'] ?? '';

    if ($email === '' || $mot_de_passe === '') {
        $erreur = "Email et mot de passe sont obligatoires.";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE email = ?");
        $stmt->execute([$email]);
        $utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($utilisateur && password_verify($mot_de_passe, $utilisateur['mot_de_passe'])) {
            $_SESSION['idu']    = $utilisateur['id'];
            $_SESSION['prenom'] = $utilisateur['prenom'];
            $_SESSION['nom']    = $utilisateur['nom'];
            // Compatibilité : `role` peut ne pas exister selon la version de la base.
            $_SESSION['role']   = $utilisateur['role'] ?? 'etudiant';

            $stmt = $pdo->prepare("UPDATE utilisateurs SET statut = 'connecte' WHERE id = ?");
            $stmt->execute([$utilisateur['id']]);

            $stmt = $pdo->prepare(
                "INSERT INTO connexions (utilisateur_id, adresse_ip) VALUES (?, ?)"
            );
            $stmt->execute([$utilisateur['id'], $_SERVER['REMOTE_ADDR'] ?? null]);

            header('Location: ' . ($_SESSION['role'] === 'admin' ? 'admin_users.php' : 'dashboard.php'));
            exit;
        } else {
            $erreur = "Email ou mot de passe incorrect.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Connexion — QCM Anti-Triche</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="page-auth">
    <div class="auth-logo"> </div>
    <div class="auth-card">

        <h1>Connexion</h1>

        <?php if (isset($_GET['inscrit'])): ?>
        <div class="msg-succes">Inscription réussie ! Vous pouvez vous connecter.</div>
        <?php endif; ?>

        <?php if ($erreur): ?>
        <div class="msg-erreur"><?= htmlspecialchars($erreur) ?></div>
        <?php endif; ?>

        <form method="post" action="connexion.php">

            <div class="form-row">
                <label class="form-label" for="email">Adresse email</label>
                <input class="form-input" type="email" id="email" name="email"
                       placeholder="exemple@email.com" required autofocus>
            </div>

            <div class="form-row">
                <label class="form-label" for="mot_de_passe">Mot de passe</label>
                <input class="form-input" type="password" id="mot_de_passe" name="mot_de_passe"
                       placeholder="Votre mot de passe" required>
            </div>

            <button class="btn-auth" type="submit">Se connecter</button>
        </form>

        <p class="auth-footer">Pas encore de compte ? <a href="inscription.php">S'inscrire</a></p>

    </div>
</div>

</body>
</html>