<?php
/**
 * À inclure en tout début de chaque page protégée.
 * require_once __DIR__ . '/includes/auth_check.php';
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['idu'])) {
    header('Location: connexion.php');
    exit;
}
