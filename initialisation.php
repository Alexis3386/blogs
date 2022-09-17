<?php
require_once('./init.php');

$query = file_get_contents('dbblogs.sql');
$pdo->exec($query);
