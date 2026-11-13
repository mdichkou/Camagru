<?php
$DB_DSN = 'mysql:host=' . (getenv('DB_HOST') ?: 'db')
    . ';dbname=' . (getenv('DB_NAME') ?: 'camagru') . ';charset=utf8mb4';
$DB_USER = getenv('DB_USER') ?: 'camagru';
$DB_PASSWORD = getenv('DB_PASSWORD');
?>
