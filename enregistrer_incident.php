<?php
session_start();
require_once __DIR__ . '/config/db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'POST attendu.']);
    exit;
}

// -------------------------------------------------------
// sendBeacon envoie du JSON brut (pas du FormData)
// On détecte le Content-Type pour lire le bon format
// -------------------------------------------------------
$contentType = $_SERVER['CONTENT_TYPE'] ?? '';

if (str_contains($contentType, 'application/json')) {
    // Appel via sendBeacon (fermeture onglet/navigateur)
    $data         = json_decode(file_get_contents('php://input'), true);
    $tentative_id = isset($data['tentative_id']) ? (int)$data['tentative_id'] : 0;

    if ($tentative_id <= 0) {
        echo json_encode(['success' => false, 'error' => 'ID tentative invalide.']);
        exit;
    }

    if (!isset($_SESSION['idu'])) {
        http_response_code(403);
        exit;
    }

    $stmt = $pdo->prepare(
        "UPDATE tentatives
         SET statut = 'abandonne'
         WHERE id = ?
           AND utilisateur_id = ?
           AND statut = 'en_cours'"
    );
    $stmt->execute([$tentative_id, $_SESSION['idu']]);
    echo json_encode(['success' => true, 'message' => 'Tentative abandonnée.']);
    exit;
}

// -------------------------------------------------------
// Appels classiques via FormData
// -------------------------------------------------------
$tentative_id  = isset($_POST['tentative_id'])  ? intval($_POST['tentative_id'])       : 0;
$type_incident = isset($_POST['type_incident']) ? trim($_POST['type_incident'])         : '';

if ($tentative_id <= 0) {
    echo json_encode(['success' => false, 'error' => 'ID tentative invalide.']);
    exit;
}

try {
    // Gestion de la sortie définitive du plein écran
    if (isset($_POST['action']) && $_POST['action'] === 'invalider_tentative') {
        $stmt = $pdo->prepare(
            "UPDATE tentatives SET statut = 'abandonne' WHERE id = :tentative_id"
        );
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

    $stmt = $pdo->prepare(
        "INSERT INTO incidents_triche (tentative_id, type_incident) VALUES (:tentative_id, :type_incident)"
    );
    $stmt->execute([
        ':tentative_id'  => $tentative_id,
        ':type_incident' => $type_incident
    ]);

    echo json_encode(['success' => true]);

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'Erreur de base de données.']);
}