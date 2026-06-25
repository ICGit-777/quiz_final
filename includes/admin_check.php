<?php
/**
 * À inclure en tout début des pages réservées à l'admin.
 * require_once __DIR__ . '/includes/admin_check.php';
 */
require_once __DIR__ . '/auth_check.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    http_response_code(403);
    echo "Accès refusé : cette page est réservée aux administrateurs.";
    exit;
}
