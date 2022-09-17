<?php

require_once('init.php');
use App\Util;

$target_dir = "assets/img";
$uploadOk = 1;
$target_file = $target_dir . basename($_FILES["image_principale"]["name"]);
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

if ($user == null or !$user->isadmin()) {
    header('Location: /');
    exit();
}

render('addPost.twig');

if (isset($_POST)) {
    var_dump($_POST);
    var_dump($_FILES);
    // titre
    // chapo
    // content
    // image_principale
}