<?php
session_start();
require_once __DIR__ . '/config/db.php';

if (!isset($_SESSION['idu'])) {
    header('Location: connexion.php');
    exit;
}

$utilisateur_id = $_SESSION['idu'];

$stmtUser = $pdo->prepare("SELECT nom, prenom FROM utilisateurs WHERE id = ?");
$stmtUser->execute([$utilisateur_id]);
$user = $stmtUser->fetch(PDO::FETCH_ASSOC);

$stmtEnCours = $pdo->prepare("SELECT COUNT(*) FROM tentatives WHERE utilisateur_id = ? AND statut = 'en_cours'");
$stmtEnCours->execute([$utilisateur_id]);
$quizEnCours = $stmtEnCours->fetchColumn() > 0;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tableau de bord</title>
    <link rel="stylesheet" href="assets/css/feuille_style.css">
    <style>
        body {
            font-family: sans-serif;
            background: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        main {
            max-width: 500px;
            margin: 40px auto;
        }
        .dashboard-header {
            background: linear-gradient(135deg, #0a2e2a, #0d3d38);
            color: white;
            padding: 25px 30px;
            border-radius: 12px;
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .dashboard-header h1 {
            margin: 0;
            font-size: 1.4rem;
            color: #74d7c4;
        }
        .dashboard-header p {
            margin: 5px 0 0;
            color: #b2d2ce;
            font-size: 0.9rem;
        }
        .dashboard-header .date {
            color: #b2d2ce;
            font-size: 0.9rem;
        }
        nav {
            margin-bottom: 20px;
        }
        nav a {
            color: #00b894;
            text-decoration: none;
            font-weight: bold;
        }
        nav a:hover { text-decoration: underline; }
        .sep { margin: 0 8px; color: #ccc; }
        .boutons {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        .boutons a {
            display: block;
            text-align: center;
            padding: 18px;
            border-radius: 10px;
            font-size: 1.1rem;
            font-weight: bold;
            text-decoration: none;
            transition: opacity 0.15s;
        }
        .boutons a:hover { opacity: 0.9; }
        .btn-quiz {
            background: linear-gradient(135deg, #00b894, #00a381);
            color: white;
        }
        .btn-histo {
            background: #0d3d38;
            color: #74d7c4;
            border: 2px solid #74d7c4;
        }
        .btn-disabled {
            background: #ccc;
            color: #666;
            cursor: not-allowed;
            pointer-events: none;
            display: block;
            text-align: center;
            padding: 18px;
            border-radius: 10px;
            font-size: 1.1rem;
            font-weight: bold;
        }
        .alerte-encours {
            background: #fff3cd;
            border: 1px solid #f39c12;
            border-radius: 8px;
            padding: 12px 18px;
            margin-bottom: 20px;
            color: #856404;
            font-weight: bold;
            text-align: center;
        }
    </style>
</head>
<body>
<main>

    <nav>
        <a href="dashboard.php">Accueil</a>
        <span class="sep">|</span>
        <a href="deconnexion.php">Déconnexion</a>
    </nav>

    <div class="dashboard-header">
        <div>
            <h1>Bonjour, <?= htmlspecialchars($user['prenom'] . ' ' . $user['nom']) ?> 👋</h1>
            <p>Bienvenue sur votre espace personnel</p>
        </div>
        <div class="date"><?= date('d/m/Y') ?></div>
    </div>

    <?php if ($quizEnCours): ?>
        <div class="alerte-encours">
            ⚠️ Vous avez un quiz en cours !
            <a href="quiz.php" style="color:#856404;">Reprendre</a>
        </div>
    <?php endif; ?>

    <div class="boutons">
        <?php if ($quizEnCours): ?>
            <span class="btn-disabled">🔒 Quiz déjà en cours</span>
        <?php else: ?>
            <a href="quiz.php" class="btn-quiz">🎯 Commencer un quiz</a>
        <?php endif; ?>
        <a href="historique.php" class="btn-histo">📋 Voir mon historique</a>
    </div>

</main>
</body>
</html>