<?php
session_start();
require_once __DIR__ . '/config/db.php';

$erreur = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom        = trim($_POST['nom'] ?? '');
    $prenom     = trim($_POST['prenom'] ?? '');
    $email      = trim($_POST['email'] ?? '');
    $mot_de_passe = $_POST['mot_de_passe'] ?? '';

    if ($nom === '' || $prenom === '' || $email === '' || $mot_de_passe === '') {
        $erreur = "Tous les champs sont obligatoires.";
    } else {
        $stmt = $pdo->prepare("SELECT id FROM utilisateurs WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $erreur = "Cet email est déjà utilisé.";
        } else {
            $hash = password_hash($mot_de_passe, PASSWORD_DEFAULT);
            // Compatibilité : la colonne `role` peut ne pas exister selon la version de la base.
            $stmtRole = $pdo->query("SHOW COLUMNS FROM utilisateurs LIKE 'role'");
            $colRoleExiste = (bool) $stmtRole->fetch();

            if ($colRoleExiste) {
                $stmt = $pdo->prepare(
                    "INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, role, statut)
                     VALUES (?, ?, ?, ?, 'etudiant', 'deconnecte')"
                );
                $stmt->execute([$nom, $prenom, $email, $hash]);
            } else {
                $stmt = $pdo->prepare(
                    "INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, statut)
                     VALUES (?, ?, ?, ?, 'deconnecte')"
                );
                $stmt->execute([$nom, $prenom, $email, $hash]);
            }
            header('Location: connexion.php?inscrit=1');
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Inscription — QCM Anti-Triche</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="page-auth">
    <div class="auth-logo"> </div>
    <div class="auth-card">

        <h1>Créer un compte</h1>

        <?php if ($erreur): ?>
        <div class="msg-erreur"><?= htmlspecialchars($erreur) ?></div>
        <?php endif; ?>

        <form method="post" action="inscription.php">

            <div class="form-row">
                <label class="form-label" for="nom">Nom</label>
                <input class="form-input" type="text" id="nom" name="nom"
                       value="<?= htmlspecialchars($_POST['nom'] ?? '') ?>"
                       placeholder="Votre nom" required autofocus>
            </div>

            <div class="form-row">
                <label class="form-label" for="prenom">Prénom</label>
                <input class="form-input" type="text" id="prenom" name="prenom"
                       value="<?= htmlspecialchars($_POST['prenom'] ?? '') ?>"
                       placeholder="Votre prénom" required>
            </div>

            <div class="form-row">
                <label class="form-label" for="email">Adresse email</label>
                <input class="form-input" type="email" id="email" name="email"
                       value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                       placeholder="exemple@email.com" required>
            </div>

            <div class="form-row">
                <label class="form-label" for="mot_de_passe">Mot de passe</label>
                <input class="form-input" type="password" id="mot_de_passe" name="mot_de_passe"
                       placeholder="Choisissez un mot de passe" required>
            </div>

            <button class="btn-auth" type="submit">S'inscrire</button>
        </form>

        <p class="auth-footer">Déjà inscrit ? <a href="connexion.php">Se connecter</a></p>

    </div>
</div>

</body>
</html>