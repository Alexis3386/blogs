<?php

namespace App\Entity;

class Photo {
    public function __construct (private int $id, private string $path, private int $idPost) {
        
    }

    public function getId() : int {
        return $this->id;
    }

    public function getPath() : string {
        return $this->libelle;
    }

    public function getIdPost(): int {
        return $this->idPost;
    }
}
