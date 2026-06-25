<?php
session_start();
require_once __DIR__ . '/config/db.php';

if (isset($_SESSION['idu'])) {
    $id = $_SESSION['idu'];

    $stmt = $pdo->prepare("UPDATE utilisateurs SET statut = 'deconnecte' WHERE id = ?");
    $stmt->execute([$id]);

    $stmt = $pdo->prepare("INSERT INTO deconnexions (utilisateur_id) VALUES (?)");
    $stmt->execute([$id]);
}

$_SESSION = [];
session_destroy();

header('Location: connexion.php');
exit;
