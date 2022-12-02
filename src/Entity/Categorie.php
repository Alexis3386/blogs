<?php

namespace App\Entity;

class Categorie
{
    public function __construct(private int $id, private string $libelle)
    {
        
    }

    public function getId() : int
    {
        return $this->id;
    }

    public function getLibelle() : string
    {
        return $this->libelle;
    }
}