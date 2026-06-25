<?php
session_start();
// historique.php — Personne B

require_once __DIR__ . '/config/db.php';

if (!isset($_SESSION['idu'])) {
    header('Location: connexion.php');
    exit;
}

$sql = "SELECT t.id, t.score, t.total_questions, t.date_tentative
        FROM tentatives t
        WHERE t.utilisateur_id = ? AND t.statut = 'termine'
        ORDER BY t.date_tentative DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute([$_SESSION['idu']]);
$tentatives = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Calcul de la moyenne générale sur 20 (exigée par le cahier des charges)
$moyenne_20 = 0;
if (!empty($tentatives)) {
    $somme_notes_20 = 0;
    foreach ($tentatives as $t) {
        if ($t['total_questions'] > 0) {
            $somme_notes_20 += ($t['score'] / $t['total_questions']) * 20;
        }
    }
    $moyenne_20 = round($somme_notes_20 / count($tentatives), 1);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mon historique</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body { font-family: sans-serif; padding: 20px; }
        .table-historique { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .table-historique th, .table-historique td { border: 1px solid #aaa; padding: 10px; }
        .moyenne-bloc { padding: 15px; background: #eef; margin-bottom: 20px; font-size: 1.1rem; }
    </style>
</head>
<body>

<main>
    <h1>Mon historique de quiz</h1>
    <hr>

    <div class="moyenne-bloc">
        Moyenne générale : <strong><?= $moyenne_20 ?> / 20</strong>
        (<?= count($tentatives) ?> tentative<?= count($tentatives) > 1 ? 's' : '' ?>)
    </div>

    <?php if (empty($tentatives)): ?>
        <p>Vous n'avez pas encore passé de quiz.</p>
        <a href="quiz.php">Commencer un quiz</a>
    <?php else: ?>
        <table class="table-historique">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Date</th>
                    <th>Score</th>
                    <th>Note /20</th>
                    <th>Détail</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($tentatives as $i => $t):
                $note = $t['total_questions'] > 0
                    ? round($t['score'] / $t['total_questions'] * 20, 1)
                    : 0;
            ?>
                <tr>
                    <td><?= $i + 1 ?></td>
                    <td><?= htmlspecialchars($t['date_tentative']) ?></td>
                    <td><?= (int)$t['score'] ?> / <?= (int)$t['total_questions'] ?></td>
                    <td><?= $note ?> / 20</td>
                    <td>
                        <a href="resultat.php?tentative_id=<?= (int)$t['id'] ?>">
                            Voir correction
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <br>
    <a href="quiz.php">Nouveau quiz</a>
</main>

</body>
</html>
