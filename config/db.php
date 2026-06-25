<?php
/**
 * Connexion PDO commune au projet.
 * Tout le monde inclut CE fichier (chemin unique : config/db.php).
 *
 * require_once __DIR__ . '/config/db.php';
 */

$host   = 'localhost';
$dbname = 'qcm_app';
$user   = 'root';
$pass   = 'root'; // mot de passe par défaut sur MAMP
$port   = '3306'; // MAMP utilise souvent le port 8889 au lieu de 3306 par défaut !
                   // Si l'erreur de connexion persiste après avoir corrigé le mot de passe,
                   // change cette valeur en '8889' (visible dans l'onglet "Ports" de MAMP).

try {
    $pdo = new PDO(
        "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4",
        $user,
        $pass,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
} catch (PDOException $e) {
    die('Erreur de connexion à la base de données : ' . $e->getMessage());
}