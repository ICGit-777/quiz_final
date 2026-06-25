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

// Vérification que la tentative appartient à cet utilisateur et est bien en cours
$stmtOwner = $pdo->prepare(
    "SELECT id FROM tentatives WHERE id = ? AND utilisateur_id = ? AND statut = 'en_cours'"
);
$stmtOwner->execute([$tentative_id, $utilisateur_id]);
if (!$stmtOwner->fetch()) {
    die('Tentative introuvable ou déjà terminée.');
}

// Clôture immédiate de la tentative pour éviter toute double soumission
$stmtFin = $pdo->prepare(
    "UPDATE tentatives SET statut = 'terminé', date_fin = NOW() WHERE id = ? AND statut = 'en_cours'"
);
$stmtFin->execute([$tentative_id]);

// Si aucune ligne n'a été affectée, une autre requête nous a devancés (double clic, etc.)
if ($stmtFin->rowCount() === 0) {
    die('Ce quiz a déjà été soumis.');
}

$score = 0;
$total = 0;

foreach ($_POST as $key => $reponse_soumise) {
    if (strpos($key, 'q_') !== 0) {
        continue;
    }

    $question_id   = (int)substr($key, 2);
    $reponse_donnee = trim($reponse_soumise); // Lettre 'A', 'B', 'C' ou 'D'
    $total++;

    // Récupère la bonne réponse
    $stmtVerite = $pdo->prepare("SELECT bonne_reponse FROM questions WHERE id = ?");
    $stmtVerite->execute([$question_id]);
    $q = $stmtVerite->fetch(PDO::FETCH_ASSOC);

    $est_correcte = ($q && $q['bonne_reponse'] === $reponse_donnee) ? 1 : 0;
    if ($est_correcte) {
        $score++;
    }

    $stmtInsert = $pdo->prepare(
        "INSERT INTO reponses_utilisateur (tentative_id, question_id, reponse_donnee, est_correcte)
         VALUES (?, ?, ?, ?)"
    );
    $stmtInsert->execute([$tentative_id, $question_id, $reponse_donnee, $est_correcte]);
}

// Mise à jour du score et du total (statut déjà à 'terminé' depuis le début)
$stmtScore = $pdo->prepare(
    "UPDATE tentatives SET score = ?, total_questions = ? WHERE id = ?"
);
$stmtScore->execute([$score, $total, $tentative_id]);

// Suppression des données de session liées au quiz en cours
unset($_SESSION['tentative_id'], $_SESSION['quiz_questions'], $_SESSION['quiz_debut']);

header("Location: resultat.php?tentative_id=" . $tentative_id);
exit;