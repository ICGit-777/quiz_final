<?php
session_start();
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/admin_check.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['supprimer_id'])) {
    $id_a_supprimer = (int) $_POST['supprimer_id'];

    if ($id_a_supprimer === (int) $_SESSION['idu']) {
        $message = "Vous ne pouvez pas supprimer votre propre compte.";
    } else {
        $stmt = $pdo->prepare("DELETE FROM utilisateurs WHERE id = ?");
        $stmt->execute([$id_a_supprimer]);
        $message = "Utilisateur supprimé.";
    }
}

$stmt = $pdo->query("SELECT id, nom, prenom, email, role, statut, date_inscription FROM utilisateurs ORDER BY id ASC");
$utilisateurs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Administration - Utilisateurs</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<main>
    <h1>Administration — Gestion des utilisateurs</h1>

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

    <table>

        <thead>
            <tr>
                <th>ID</th><th>Nom</th><th>Prénom</th><th>Email</th>
                <th>Rôle</th><th>Statut</th><th>Inscrit le</th><th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($utilisateurs as $u): ?>
                <tr>
                    <td><?= $u['id'] ?></td>
                    <td><?= htmlspecialchars($u['nom']) ?></td>
                    <td><?= htmlspecialchars($u['prenom']) ?></td>
                    <td><?= htmlspecialchars($u['email']) ?></td>
                    <td><?= htmlspecialchars($u['role']) ?></td>
                    <td><?= htmlspecialchars($u['statut']) ?></td>
                    <td><?= htmlspecialchars($u['date_inscription']) ?></td>
                    <td>
                        <form method="post" action="admin_users.php" style="display:inline;"
                              onsubmit="return confirm('Supprimer cet utilisateur ?');">
                            <input type="hidden" name="supprimer_id" value="<?= $u['id'] ?>">
                            <button type="submit" class="btn-supprimer">Supprimer</button>
                        </form>

                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</main>
</body>
</html>

