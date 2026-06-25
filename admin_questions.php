<?php
// admin_questions.php
session_start();
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/admin_check.php';

$message = '';
$question_edit = null;

// ---- SUPPRESSION ----
if (isset($_GET['supprimer'])) {
    $id = (int)$_GET['supprimer'];
    $stmt = $pdo->prepare("DELETE FROM questions WHERE id = ?");
    $stmt->execute([$id]);
    $message = 'Question supprimée avec succès.';
}

// ---- CHARGEMENT POUR MODIFICATION ----
if (isset($_GET['editer'])) {
    $id = (int)$_GET['editer'];
    $stmt = $pdo->prepare("SELECT * FROM questions WHERE id = ?");
    $stmt->execute([$id]);
    $question_edit = $stmt->fetch(PDO::FETCH_ASSOC);
}

// ---- AJOUT OU MODIFICATION ----
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_edit       = (int)($_POST['id_edit'] ?? 0);
    $question      = trim($_POST['question'] ?? '');
    $niveau        = $_POST['niveau'] ?? 'moyen';
    $categorie     = trim($_POST['categorie'] ?? 'Général');
    $rep_a         = trim($_POST['reponse_a'] ?? '');
    $rep_b         = trim($_POST['reponse_b'] ?? '');
    $rep_c         = trim($_POST['reponse_c'] ?? '');
    $rep_d         = trim($_POST['reponse_d'] ?? '');
    $bonne_reponse = $_POST['bonne_reponse'] ?? 'A';

    if ($question !== '' && $rep_a !== '' && $rep_b !== '') {
        if ($id_edit > 0) {
            $sql = "UPDATE questions SET question = ?, reponse_a = ?, reponse_b = ?, reponse_c = ?, reponse_d = ?, bonne_reponse = ?, categorie = ?, niveau = ? WHERE id = ?";
            $pdo->prepare($sql)->execute([$question, $rep_a, $rep_b, $rep_c, $rep_d, $bonne_reponse, $categorie, $niveau, $id_edit]);
            $message = 'Question modifiée.';
        } else {
            $sql = "INSERT INTO questions (question, reponse_a, reponse_b, reponse_c, reponse_d, bonne_reponse, categorie, niveau) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $pdo->prepare($sql)->execute([$question, $rep_a, $rep_b, $rep_c, $rep_d, $bonne_reponse, $categorie, $niveau]);
            $message = 'Question ajoutée.';
        }
        $question_edit = null;
    }
}

// ---- LISTE DES QUESTIONS ----
$questions = $pdo->query("SELECT * FROM questions ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Administration des Questions</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<main>
    <h1>Gestion des questions</h1>

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

    <form method="POST" action="admin_questions.php">

        <input type="hidden" name="id_edit" value="<?= (int)($question_edit['id'] ?? 0) ?>">

        <label>Question : <input type="text" name="question" value="<?= htmlspecialchars($question_edit['question'] ?? '') ?>" required></label><br>
        <label>Catégorie : <input type="text" name="categorie" value="<?= htmlspecialchars($question_edit['categorie'] ?? '') ?>"></label><br>
        <label>Niveau :
            <select name="niveau">
                <option value="facile" <?= ($question_edit['niveau'] ?? '') == 'facile' ? 'selected' : '' ?>>Facile</option>
                <option value="moyen" <?= ($question_edit['niveau'] ?? '') == 'moyen' ? 'selected' : '' ?>>Moyen</option>
                <option value="difficile" <?= ($question_edit['niveau'] ?? '') == 'difficile' ? 'selected' : '' ?>>Difficile</option>
            </select>
        </label><br>
        <label>Réponse A : <input type="text" name="reponse_a" value="<?= htmlspecialchars($question_edit['reponse_a'] ?? '') ?>" required></label><br>
        <label>Réponse B : <input type="text" name="reponse_b" value="<?= htmlspecialchars($question_edit['reponse_b'] ?? '') ?>" required></label><br>
        <label>Réponse C : <input type="text" name="reponse_c" value="<?= htmlspecialchars($question_edit['reponse_c'] ?? '') ?>"></label><br>
        <label>Réponse D : <input type="text" name="reponse_d" value="<?= htmlspecialchars($question_edit['reponse_d'] ?? '') ?>"></label><br>
        <label>Bonne réponse :
            <select name="bonne_reponse">
                <option value="A" <?= ($question_edit['bonne_reponse'] ?? '') == 'A' ? 'selected' : '' ?>>A</option>
                <option value="B" <?= ($question_edit['bonne_reponse'] ?? '') == 'B' ? 'selected' : '' ?>>B</option>
                <option value="C" <?= ($question_edit['bonne_reponse'] ?? '') == 'C' ? 'selected' : '' ?>>C</option>
                <option value="D" <?= ($question_edit['bonne_reponse'] ?? '') == 'D' ? 'selected' : '' ?>>D</option>
            </select>
        </label><br>
        <button type="submit"><?= $question_edit ? 'Enregistrer les modifications' : 'Ajouter la question' ?></button>
    </form>

    <h2>Liste des questions</h2>
    <table>

        <thead>
            <tr><th>ID</th><th>Question</th><th>Bonne réponse</th><th>Catégorie</th><th>Niveau</th><th>Action</th></tr>
        </thead>
        <tbody>
            <?php foreach ($questions as $q): ?>
                <tr>
                    <td><?= $q['id'] ?></td>
                    <td><?= htmlspecialchars($q['question']) ?></td>
                    <td><?= htmlspecialchars($q['bonne_reponse']) ?></td>
                    <td><?= htmlspecialchars($q['categorie'] ?? '') ?></td>
                    <td><?= htmlspecialchars($q['niveau']) ?></td>
                    <td>
                        <a class="action-link" href="admin_questions.php?editer=<?= $q['id'] ?>">Modifier</a>
                        <span class="sep">|</span>
                        <a class="btn-supprimer" href="admin_questions.php?supprimer=<?= $q['id'] ?>" onclick="return confirm('Supprimer cette question ?');" style="display:inline-block;">Supprimer</a>
                    </td>

                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</main>
</body>
</html>

