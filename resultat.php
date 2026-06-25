<?php
// resultat.php — Personne B
session_start();
require_once __DIR__ . '/config/db.php';

if (!isset($_SESSION['idu'])) {
    die('Accès refusé.');
}

$tentative_id = isset($_GET['tentative_id']) ? (int)$_GET['tentative_id'] : 0;
if ($tentative_id === 0) {
    header('Location: quiz.php');
    exit;
}

$stmtT = $pdo->prepare(
    "SELECT * FROM tentatives WHERE id = ? AND utilisateur_id = ? AND statut = 'termine'"
);
$stmtT->execute([$tentative_id, $_SESSION['idu']]);
$tentative = $stmtT->fetch(PDO::FETCH_ASSOC);

if (!$tentative) {
    header('Location: quiz.php');
    exit;
}

// Requête alignée sur la vraie colonne de la base : question_id (pas idq)
$stmtRep = $pdo->prepare(
    "SELECT
        q.question, q.reponse_a, q.reponse_b, q.reponse_c, q.reponse_d, q.bonne_reponse,
        ru.reponse_donnee, ru.est_correcte
     FROM reponses_utilisateur ru
     JOIN questions q ON q.id = ru.question_id
     WHERE ru.tentative_id = ?"
);
$stmtRep->execute([$tentative_id]);
$reponses_brutes = $stmtRep->fetchAll(PDO::FETCH_ASSOC);

$detail = [];
foreach ($reponses_brutes as $row) {
    $mapping = [
        'A' => $row['reponse_a'],
        'B' => $row['reponse_b'],
        'C' => $row['reponse_c'],
        'D' => $row['reponse_d']
    ];

    $reponse_choisie_texte = $mapping[$row['reponse_donnee']] ?? $row['reponse_donnee'];
    $bonne_reponse_texte   = $mapping[$row['bonne_reponse']] ?? $row['bonne_reponse'];

    $detail[] = [
        'question'        => $row['question'],
        'reponse_choisie' => $reponse_choisie_texte,
        'bonne_reponse'   => $bonne_reponse_texte,
        'est_correcte'    => $row['est_correcte']
    ];
}

$score = $tentative['score'];
$total = $tentative['total_questions'];
$pourcentage = $total > 0 ? round($score / $total * 100) : 0;

// Note sur 20, comme demandé par le cahier des charges
$note_sur_20 = $total > 0 ? round(($score / $total) * 20, 1) : 0;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Résultats</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body { font-family: sans-serif; padding: 20px; }

        .top-right {
            position: fixed;
            top: 15px;
            right: 20px;
        }
        .top-right a {
            color: #0645ad;
            text-decoration: none;
            font-weight: 600;
        }
        .top-right a:hover {
            text-decoration: underline;
        }

        .score-bloc { padding: 15px; background: #eef; margin-bottom: 20px; }
        .table-correction { width: 100%; border-collapse: collapse; margin-top: 15px; }
        .table-correction th, .table-correction td { border: 1px solid #ccc; padding: 8px; }
    </style>
</head>
<body>

    <div class="top-right">
        <a href="deconnexion.php">Déconnexion</a>
    </div>

<main>
    <h1>Vos résultats</h1>
    <hr>
    <div class="score-bloc">
        <p>Vous avez obtenu <strong><?= $score ?> / <?= $total ?></strong> bonnes réponses (<?= $pourcentage ?> %)</p>
        <p>Note : <strong><?= $note_sur_20 ?> / 20</strong></p>
    </div>
    <h2>Correction détaillée</h2>
    <table class="table-correction" border="1" cellspacing="0" cellpadding="5">
        <thead>
            <tr>
                <th>#</th>
                <th>Question</th>
                <th>Votre réponse</th>
                <th>Bonne réponse</th>
                <th>Résultat</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($detail as $i => $ligne): ?>
            <tr style="background-color: <?= $ligne['est_correcte'] ? '#d4edda' : '#f8d7da' ?>;">
                <td><?= $i + 1 ?></td>
                <td><?= htmlspecialchars($ligne['question']) ?></td>
                <td><?= htmlspecialchars($ligne['reponse_choisie']) ?></td>
                <td><?= htmlspecialchars($ligne['bonne_reponse']) ?></td>
                <td><?= $ligne['est_correcte'] ? 'Vrai' : 'Faux' ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <br>
    <a href="historique.php">Voir mon historique</a>
</main>

</body>
</html>
