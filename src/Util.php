<?php

namespace App;

class Util
{
    public static function validStringNotEmpty($donnees, $name): bool
    {
        if (!isset($donnees[$name])) {
            return false;
        }
        $donnees[$name] = trim($donnees[$name]);
        return !empty($donnees[$name]);
    }

    public static function checkMAilFormat($donnees, $name): bool
    {
        if (($name === 'email') && !filter_var($donnees[$name], FILTER_VALIDATE_EMAIL)) {
            return false;
        }
    }

    public static function checkMdpFormat($mdp): bool
    {
        $majuscule = preg_match('@[A-Z]@', $mdp);
        $minuscule = preg_match('@[a-z]@', $mdp);
        $chiffre = preg_match('@\d@', $mdp);

        return (!$majuscule || !$minuscule || !$chiffre || strlen($mdp) < 8);
    }

    public static function checkFileFormat($file) : bool
    {
        return getimagesize($file['tmp_name']);
    }

    public static function checkFileSize($file): bool
    {
        return $file['size'] > 500000;
    }

}
