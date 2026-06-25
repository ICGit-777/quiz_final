<?php
session_start();
// quiz.php — Personne B

require_once __DIR__ . '/config/db.php';

if (!isset($_SESSION['idu'])) {
    die('<p>Vous devez vous connecter pour passer un quiz. <a href="connexion.php">Connexion</a></p>');
}

$utilisateur_id = $_SESSION['idu'];

// -------------------------------------------------------
// Vérification anti-double-session
// -------------------------------------------------------
$stmtCheck = $pdo->prepare(
    "SELECT COUNT(*) FROM tentatives WHERE utilisateur_id = ? AND statut = 'en_cours'"
);
$stmtCheck->execute([$utilisateur_id]);
if ($stmtCheck->fetchColumn() > 0) {
    die('<p>Vous avez déjà un quiz en cours. Terminez-le avant d\'en commencer un nouveau.</p>');
}

// -------------------------------------------------------
// Création de la ligne tentative (statut en_cours)
// -------------------------------------------------------
$stmtTentative = $pdo->prepare(
    "INSERT INTO tentatives (utilisateur_id, statut, date_tentative) VALUES (?, 'en_cours', NOW())"
);
$stmtTentative->execute([$utilisateur_id]);
$tentative_id = $pdo->lastInsertId();
unset($stmtTentative);

// -------------------------------------------------------
// Récupération de 10 questions aléatoires
// -------------------------------------------------------
$stmtQ = $pdo->query("SELECT * FROM questions ORDER BY RAND() LIMIT 10");
$questions = $stmtQ->fetchAll(PDO::FETCH_ASSOC);
unset($stmtQ);

// -------------------------------------------------------
// Durée du QCM configurée par l'admin (table parametres)
// Fallback à 10 minutes si la table n'existe pas (ou si erreur SQL)
// -------------------------------------------------------
$duree_minutes = 10;
try {
    $stmtParam = $pdo->query("SELECT duree_qcm_minutes FROM parametres WHERE id = 1");
    $param = $stmtParam->fetch(PDO::FETCH_ASSOC);
    if ($param && isset($param['duree_qcm_minutes'])) {
        $duree_minutes = (int)$param['duree_qcm_minutes'];
        if ($duree_minutes <= 0) {
            $duree_minutes = 10;
        }
    }
} catch (Throwable $e) {
    // Utiliser la valeur par défaut
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Quiz en cours</title>
    <style>
        body { font-family: sans-serif; padding: 30px; background: #fafafa; }
        .question-bloc { margin-bottom: 25px; padding: 15px; background: #fff; border: 1px solid #eee; border-radius: 5px; }
    </style>
</head>
<body>

<main>
    <h1>Quiz</h1>
    <p>Répondez aux questions ci-dessous.</p>

    <form
        id="quizForm"
        action="valider_reponse.php"
        method="post"
        data-tentative-id="<?= $tentative_id ?>"
        data-duree-minutes="<?= (int) $duree_minutes ?>"
    >
        <input type="hidden" name="tentative_id" value="<?= $tentative_id ?>">

        <?php
        $numero = 1;
        foreach ($questions as $question):
            $question_id = (int)$question['id'];

            $options = [
                'A' => $question['reponse_a'],
                'B' => $question['reponse_b'],
                'C' => $question['reponse_c'],
                'D' => $question['reponse_d']
            ];
        ?>
        <div class="question-bloc">
            <h3><?= $numero ?>. <?= htmlspecialchars($question['question']) ?></h3>

            <?php foreach ($options as $lettre => $texte_reponse):
                if ($texte_reponse === '' || $texte_reponse === null) continue;
            ?>
                <label>
                    <input
                        type="radio"
                        name="q_<?= $question_id ?>"
                        value="<?= $lettre ?>"
                        required
                    >
                    <strong><?= $lettre ?> :</strong> <?= htmlspecialchars($texte_reponse) ?>
                </label><br>
            <?php endforeach; ?>
        </div>
        <?php
            $numero++;
        endforeach;
        ?>

        <br>
        <button type="submit">Valider le quiz</button>
    </form>
</main>

<script src="assets/js/anticheat.js"></script>
</body>
</html>

