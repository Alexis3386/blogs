<?php
require_once(dirname(dirname(__FILE__)) . '/init.php');

$query = file_get_contents('dbblogs.sql');
$pdo->exec($query);