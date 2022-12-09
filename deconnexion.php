<?php
require_once('init.php');
session_destroy();
$user_connecte = false;
header('Location: /');
