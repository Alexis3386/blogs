<?php

namespace App;

class Util
{
    static public function validStringNotEmpty($donnees, $name)
    {
        if (!isset($donnees[$name])) {
            return false;
        }
        if ($name == 'email') {
            if (!filter_var($donnees[$name], FILTER_VALIDATE_EMAIL)) {
                exit('Merci de renseigner un email valid');
            };
        }
        $donnees[$name] = trim($donnees[$name]);
        return !empty($donnees[$name]);
    }

    static public function checkMdpFormat($mdp)
    {
        $majuscule = preg_match('@[A-Z]@', $mdp);
        $minuscule = preg_match('@[a-z]@', $mdp);
        $chiffre = preg_match('@[0-9]@', $mdp);

        if (!$majuscule || !$minuscule || !$chiffre || strlen($mdp) < 8) {
            return false;
        } else
            return true;
    }
}
