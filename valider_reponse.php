<?php
// valider_reponse.php — Personne B
session_start();
require_once __DIR__ . '/config/db.php';

if (!isset($_SESSION['idu'])) {
    die('Accès refusé.');
}

$utilisateur_id = $_SESSION['idu'];

$tentative_id = isset($_POST['tentative_id']) ? (int)$_POST['tentative_id'] : 0;
if ($tentative_id === 0) {
    die('Tentative invalide.');
}

// Vérification de la tentative en cours
$stmtOwner = $pdo->prepare(
    "SELECT id FROM tentatives WHERE id = ? AND utilisateur_id = ? AND statut = 'en_cours'"
);
$stmtOwner->execute([$tentative_id, $utilisateur_id]);
if (!$stmtOwner->fetch()) {
    die('Tentative introuvable ou déjà terminée.');
}

$score = 0;
$total = 0;

foreach ($_POST as $key => $reponse_soumise) {
    if (strpos($key, 'q_') !== 0) {
        continue;
    }

    $question_id = (int)substr($key, 2); // Extrait l'identifiant numérique de la question
    $reponse_donnee = trim($reponse_soumise); // Lettre 'A', 'B', 'C' ou 'D'
    $total++;

    // Récupère la bonne réponse de la question
    $stmtVerite = $pdo->prepare("SELECT bonne_reponse FROM questions WHERE id = ?");
    $stmtVerite->execute([$question_id]);
    $q = $stmtVerite->fetch(PDO::FETCH_ASSOC);

    $est_correcte = ($q && $q['bonne_reponse'] === $reponse_donnee) ? 1 : 0;
    if ($est_correcte) {
        $score++;
    }

    // Insertion alignée sur la vraie colonne de la base : question_id (pas idq)
    $stmtInsert = $pdo->prepare(
        "INSERT INTO reponses_utilisateur (tentative_id, question_id, reponse_donnee, est_correcte)
         VALUES (?, ?, ?, ?)"
    );
    $stmtInsert->execute([$tentative_id, $question_id, $reponse_donnee, $est_correcte]);
}

// Mise à jour de la tentative (statut, score et total_questions)
$stmtUpdateTentative = $pdo->prepare(
    "UPDATE tentatives SET statut = 'termine', score = ?, total_questions = ? WHERE id = ?"
);
$stmtUpdateTentative->execute([$score, $total, $tentative_id]);

header("Location: resultat.php?tentative_id=" . $tentative_id);
exit;
