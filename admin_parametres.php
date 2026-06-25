<?php
session_start();
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/admin_check.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['duree_qcm_minutes'])) {
    $nouvelle_duree = (int) $_POST['duree_qcm_minutes'];

    if ($nouvelle_duree < 1 || $nouvelle_duree > 120) {
        $message = "La durée doit être comprise entre 1 et 120 minutes.";
    } else {
        $stmt = $pdo->prepare("UPDATE parametres SET duree_qcm_minutes = ? WHERE id = 1");
        $stmt->execute([$nouvelle_duree]);
        $message = "Durée mise à jour : $nouvelle_duree minutes.";
    }
}

$stmt = $pdo->query("SELECT duree_qcm_minutes FROM parametres WHERE id = 1");
$parametres = $stmt->fetch(PDO::FETCH_ASSOC);
$duree_actuelle = $parametres['duree_qcm_minutes'] ?? 10;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Administration - Paramètres</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<main>
    <h1>Administration — Paramètres</h1>

    <nav>
        <a href="admin_users.php">Utilisateurs</a>
        <span class="sep">|</span>
        <a href="admin_questions.php">Questions</a>
        <span class="sep">|</span>
        <a href="admin_parametres.php">Paramètres</a>
        <span class="sep">|</span>
        <a href="admin_triche.php">Journal anti-triche</a>
        <span class="sep">|</span>
        <a href="deconnexion.php">Déconnexion</a>
    </nav>


    <?php if ($message): ?>
        <p class="msg-succes"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <h2>Durée du QCM</h2>

    <p>Durée actuelle : <strong><?= (int) $duree_actuelle ?> minutes</strong></p>

    <form method="post" action="admin_parametres.php">
        <label>
            Nouvelle durée (en minutes) :
            <input type="number" name="duree_qcm_minutes" min="1" max="120" value="<?= (int) $duree_actuelle ?>" required>
        </label>
        <button type="submit">Enregistrer</button>
    </form>
</main>
</body>
</html>

