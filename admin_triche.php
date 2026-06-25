<?php
session_start();
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/admin_check.php';

try {
    $query = "SELECT i.id, i.type_incident, i.date_incident, t.id AS tentative_id, u.nom, u.prenom
              FROM incidents_triche i
              JOIN tentatives t ON i.tentative_id = t.id
              JOIN utilisateurs u ON t.utilisateur_id = u.id
              ORDER BY i.date_incident DESC";

    $stmt = $pdo->query($query);
    $incidents = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $incidents = [];
    $erreur = "Impossible de récupérer les logs de triche.";
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Administration — Journal des Incidents</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<main>
    <h1>🚨 Journal de Surveillance Anti-Triche</h1>

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

    <p>Liste des alertes levées en temps réel :</p>

    <?php if (isset($erreur)): ?>
        <p class="msg-erreur"><?= htmlspecialchars($erreur) ?></p>
    <?php endif; ?>

    <table>
        <thead>
        <tr>
            <th>ID</th>
            <th>Étudiant</th>
            <th>ID Tentative</th>
            <th>Type d'incident</th>
            <th>Horodatage</th>
        </tr>
        </thead>
        <tbody>
        <?php if (empty($incidents)): ?>
            <tr>
                <td colspan="5" style="text-align: center; color: var(--texte-discret);">
                    Aucun incident détecté pour le moment. ✨
                </td>
            </tr>
        <?php else: ?>
            <?php foreach ($incidents as $inc): ?>
                <tr>
                    <td>#<?= htmlspecialchars($inc['id']) ?></td>
                    <td><strong><?= htmlspecialchars($inc['nom']) . ' ' . htmlspecialchars($inc['prenom']) ?></strong></td>
                    <td>Session n°<?= htmlspecialchars($inc['tentative_id']) ?></td>
                    <td>
                        <span class="badge <?= ($inc['type_incident'] === 'changement_onglet') ? 'badge-danger' : 'badge-warning' ?>">
                            <?= htmlspecialchars(str_replace('_', ' ', $inc['type_incident'])) ?>
                        </span>
                    </td>
                    <td><?= htmlspecialchars($inc['date_incident']) ?></td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>
</main>

</body>
</html>

