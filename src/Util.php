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
           if(!filter_var($donnees[$name],FILTER_VALIDATE_EMAIL)) {
               exit('Merci de renseigner un email valid');
           };
        }
        $donnees[$name] = trim($donnees[$name]);
        return !empty($donnees[$name]);
    }
}
