<?php

namespace App;

class Util
{
    public static function validStringNotEmpty(array $donnees, string $name): bool
    {
        if (!isset($donnees[$name])) {
            return false;
        }
        $donnees[$name] = trim($donnees[$name]);
        return !empty($donnees[$name]);
    }

    public static function checkMailFormat(array $donnees, string $name): bool
    {
        if (($name === 'email') && !filter_var($donnees[$name], FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        return true;
    }

    public static function checkMdpFormat(string $mdp): bool
    {
        $majuscule = preg_match('@[A-Z]@', $mdp);
        $minuscule = preg_match('@[a-z]@', $mdp);
        $chiffre = preg_match('@\d@', $mdp);

        return (!$majuscule || !$minuscule || !$chiffre || strlen($mdp) < 8);
    }

    public static function slugify(string $text): string
    {
        $text = strip_tags($text);
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);
        setlocale(LC_ALL, 'en_US.utf8');
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        $text = preg_replace('~[^-\w]+~', '', $text);
        $text = trim($text, '-');
        $text = preg_replace('~-+~', '-', $text);
        $text = strtolower($text);
        if (empty($text)) {
            return 'n-a';
        }
        return $text;
    }
}
