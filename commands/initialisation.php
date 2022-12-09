<?php
require_once(dirname(dirname(__FILE__)) . '/init.php');

$query = file_get_contents('blogs.sql');
$pdo->exec($query);
