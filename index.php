<?php
require('init.php');

render('home.twig', [
    'lastPost' => $lastPost,
]);
