<?php
require_once('./config.php');

$query = 'TRUNCATE TABLE `users`;';
try {
    $pdo->exec($query);
    echo 'Table users clear';
} catch (PDOException $Exception) {
    throw new PDOException($Exception);
}
