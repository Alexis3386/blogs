<?php

require_once('init.php');

$upload_dir = "assets/img";


if ($user == null or !$user->isadmin()) {
    header('Location: /');
    exit();
}

if (!isset($_GET['idPost']) || empty($_GET['idPost'])) {
    header('HTTP/1.0 404 Not Found');
    exit();
}

$postUpdate = $blogpostRepository->recuperePost($_GET['idPost']);
if ($postUpdate === null) {
    header('HTTP/1.0 404 Not Found');
    exit();
}


if (isset($_POST) && !empty($_POST)) {
    $postUpdate->setTitre($_POST['titre']);
    $postUpdate->setChapo($_POST['chapo']);
    $postUpdate->setContent($_POST['content']);
    $postUpdate = $blogpostRepository->updatePost($postUpdate);

    if (isset($_POST['categorie'])) {
        $categorieRepository->associeCategorie($_POST['categorie'], $postUpdate);
    }

    // Undefined | Multiple Files | $_FILES Corruption Attack
    // If this request falls under any of them, treat it invalid.
    if (
        !isset($_FILES['image_principale']['error']) ||
        is_array($_FILES['image_principale']['error'])
    ) {
        throw new RuntimeException('Invalid parameters.');
    }

    // Check $_FILES['image_principale']['error'] value.
    switch ($_FILES['image_principale']['error']) {
        case UPLOAD_ERR_OK:
            break;
        case UPLOAD_ERR_NO_FILE:
            throw new RuntimeException('No file sent.');
        case UPLOAD_ERR_INI_SIZE:
        case UPLOAD_ERR_FORM_SIZE:
            throw new RuntimeException('Exceeded filesize limit.');
        default:
            throw new RuntimeException('Unknown errors.');
    }

    // You should also check filesize here.
    if ($_FILES['image_principale']['size'] > 1000000) {
        throw new RuntimeException('Exceeded filesize limit.');
    }

    // DO NOT TRUST $_FILES['upfile']['mime'] VALUE !!
    // Check MIME Type by yourself.
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    if (false === $ext = array_search(
        $finfo->file($_FILES['image_principale']['tmp_name']),
        array(
            'jpg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
        ),
        true
    )) {
        throw new RuntimeException('Invalid file format.');
    }

    // You should name it uniquely.
    // DO NOT USE $_FILES['upfile']['name'] WITHOUT ANY VALIDATION !!
    // On this example, obtain safe unique name from its binary data.
    $file_name = sprintf(
        './assets/img/%s.%s',
        sha1_file($_FILES['image_principale']['tmp_name']),
        $ext
    );
    if (!move_uploaded_file(
        $_FILES['image_principale']['tmp_name'],
        $file_name
    )) {
        throw new RuntimeException('Failed to move uploaded file.');
    } else {
        $photoRepository->enregistrer($file_name, $lastId);
    }
}

render(
    'updatePost.twig',
    [
        'categories' => $categories,
        'postUpdate' => $postUpdate,
        'categoriePostUpdates' => $categorieRepository->categorieByPost($postUpdate),
    ]
);
