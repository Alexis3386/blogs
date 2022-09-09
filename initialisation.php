<?php
require_once('./init.php');



$query = file_get_contents('exporteddbblogs.sql');
$pdo->exec($query);
