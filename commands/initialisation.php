<?php
require_once(dirname(__FILE__, 2) . '/init.php');

$query = file_get_contents('blogs.sql');
$pdo->exec($query);
