<?php
require_once('./init.php');

$query = 'TRUNCATE TABLE `users`;';

try {
    $pdo->exec($query);
    echo "Table users clear \n";
    $userRepository->enregistreUser('admin', 'admin', 'admin', 'admin@exemple.com');
    $query = "UPDATE `users` SET isadmin = 1 WHERE id = 1";
    $pdo->exec($query);
    echo 'Added first user in database';
} catch (PDOException $Exception) {
    throw new PDOException($Exception);
}
