<?php

require_once('init.php');

if ($user == null or !$user->isadmin()) {
    render('administration/administrationSignin.twig');
    exit();
}

if (isset($_POST['commentDel']) && isset($_POST['commentIdtoDel'])) {
    if ($commentaireRepository->deleteComment($_POST['commentIdtoDel'])) {
        $_SESSION['notification']['notice'] = 'Votre commentaire a bien été éffacé';
    }
}

if (isset($_POST['commentToValidate']) && !empty($_POST['commentToValidate'])) {
    if ($commentaireRepository->commentValidate($_POST['commentToValidate'])) {
        $_SESSION['notification']['notice'] = 'Le commentaire a été validé';
    }
}

$commentsPending = $commentaireRepository->findCommentPending();

render(
    'administration/adminComments.twig',
    [
        'commentsPending' => $commentsPending,
    ]
);
