<?php
session_start();
require_once __DIR__ . '/config/db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'POST attendu.']);
    exit;
}

$tentative_id = isset($_POST['tentative_id']) ? intval($_POST['tentative_id']) : 0;
$type_incident = isset($_POST['type_incident']) ? trim($_POST['type_incident']) : '';

if ($tentative_id <= 0) {
    echo json_encode(['success' => false, 'error' => 'ID tentative invalide.']);
    exit;
}

try {
    // Gestion de la sortie définitive du plein écran
    if (isset($_POST['action']) && $_POST['action'] === 'invalider_tentative') {
        $query = "UPDATE tentatives SET statut = 'abandonne' WHERE id = :tentative_id";
        $stmt = $pdo->prepare($query);
        $stmt->execute([':tentative_id' => $tentative_id]);
        echo json_encode(['success' => true, 'message' => 'Tentative marquée comme abandonnée.']);
        exit;
    }

    // Gestion de l'enregistrement classique d'un incident de triche
    $types_autorises = ['changement_onglet', 'perte_focus', 'copier_coller', 'clic_droit', 'temps_depasse', 'multi_session'];
    if (!in_array($type_incident, $types_autorises)) {
        echo json_encode(['success' => false, 'error' => 'Type incident inconnu.']);
        exit;
    }

    $query = "INSERT INTO incidents_triche (tentative_id, type_incident) VALUES (:tentative_id, :type_incident)";
    $stmt = $pdo->prepare($query);
    $stmt->execute([
        ':tentative_id' => $tentative_id,
        ':type_incident' => $type_incident
    ]);

    echo json_encode(['success' => true]);

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'Erreur de base de données.']);
}
