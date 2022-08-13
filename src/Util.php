<?php

namespace App;

class Util
{
    static public function validStringNotEmpty($donnees, $name)
    {
        if (!isset($donnees[$name])) {
            return false;
        }
        $donnees[$name] = trim($donnees[$name]);
        return !empty($donnees[$name]);
    }
}
