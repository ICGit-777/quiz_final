<?php
session_start();
require_once __DIR__ . '/config/db.php';

if (!isset($_SESSION['idu'])) {
    die('<p>Vous devez vous connecter pour passer un quiz. <a href="connexion.php">Connexion</a></p>');
}

$utilisateur_id = $_SESSION['idu'];

// -------------------------------------------------------
// Vérification si une tentative est déjà en cours
// -------------------------------------------------------
$stmtCheck = $pdo->prepare(
    "SELECT id FROM tentatives WHERE utilisateur_id = ? AND statut = 'en_cours' LIMIT 1"
);
$stmtCheck->execute([$utilisateur_id]);
$tentativeEnCours = $stmtCheck->fetch(PDO::FETCH_ASSOC);

if ($tentativeEnCours) {
    // Reprendre la tentative existante
    $tentative_id = $tentativeEnCours['id'];

    // Récupérer les questions déjà liées à cette tentative via les réponses enregistrées
    // Si aucune réponse encore, on tire des questions aléatoires et on les mémorise en session
    if (!isset($_SESSION['quiz_questions_' . $tentative_id])) {
        // Première reprise sans questions sauvegardées → nouvelles questions aléatoires
        $stmtQ = $pdo->query("SELECT * FROM questions ORDER BY RAND() LIMIT 10");
        $questions = $stmtQ->fetchAll(PDO::FETCH_ASSOC);
        $_SESSION['quiz_questions_' . $tentative_id] = array_column($questions, null, 'id');
    } else {
        $ids = array_keys($_SESSION['quiz_questions_' . $tentative_id]);
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $stmtQ = $pdo->prepare("SELECT * FROM questions WHERE id IN ($placeholders)");
        $stmtQ->execute($ids);
        $questions = $stmtQ->fetchAll(PDO::FETCH_ASSOC);
    }

    $reprise = true;
} else {
    $reprise = false;

    // -------------------------------------------------------
    // Création d'une nouvelle tentative
    // -------------------------------------------------------
    $stmtTentative = $pdo->prepare(
        "INSERT INTO tentatives (utilisateur_id, statut, date_tentative) VALUES (?, 'en_cours', NOW())"
    );
    $stmtTentative->execute([$utilisateur_id]);
    $tentative_id = $pdo->lastInsertId();

    // Récupération de 10 questions aléatoires
    $stmtQ = $pdo->query("SELECT * FROM questions ORDER BY RAND() LIMIT 10");
    $questions = $stmtQ->fetchAll(PDO::FETCH_ASSOC);

    // Sauvegarder les questions en session pour pouvoir les retrouver si reprise
    $_SESSION['quiz_questions_' . $tentative_id] = array_column($questions, null, 'id');
}

// -------------------------------------------------------
// Durée du QCM configurée par l'admin
// -------------------------------------------------------
$duree_minutes = 10;
try {
    $stmtParam = $pdo->query("SELECT duree_qcm_minutes FROM parametres WHERE id = 1");
    $param = $stmtParam->fetch(PDO::FETCH_ASSOC);
    if ($param && isset($param['duree_qcm_minutes'])) {
        $duree_minutes = (int)$param['duree_qcm_minutes'];
        if ($duree_minutes <= 0) $duree_minutes = 10;
    }
} catch (Throwable $e) {}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Quiz en cours</title>
    <link rel="stylesheet" href="assets/css/feuille_style.css">
</head>
<body>

<main>
    <h1>Quiz</h1>

    <?php if ($reprise): ?>
        <p style="background:#fff3cd; border:1px solid #f39c12; border-radius:8px; padding:10px 15px; color:#856404; font-weight:bold;">
            ⚠️ Reprise de votre quiz en cours — répondez à toutes les questions et validez.
        </p>
    <?php else: ?>
        <p>Répondez aux questions ci-dessous.</p>
    <?php endif; ?>

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
                    <input type="radio" name="q_<?= $question_id ?>" value="<?= $lettre ?>" required>
                    <strong><?= $lettre ?> :</strong> <?= htmlspecialchars($texte_reponse) ?>
                </label><br>
            <?php endforeach; ?>
        </div>
        <?php $numero++; endforeach; ?>

        <br>
        <button type="submit">Valider le quiz</button>
    </form>
</main>

<script src="assets/js/anticheat.js"></script>
</body>
</html>