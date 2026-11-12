<?php
require(__DIR__ . '/database.php');
try {
    global $pdo;
    $pdo = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Do not print connection details or continue with an unusable PDO handle.
    error_log('Database connection failed. Check the local database configuration.');
    if (PHP_SAPI === 'cli') {
        exit(1);
    }
    http_response_code(503);
    exit('Database unavailable.');
}
?>
