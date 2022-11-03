<?php

namespace App\Entity;
use DateTime;

class Commentaire {
    public function __construct (private string $content, private int $idPost, private int $idUser, private bool $isValid = false, private ?DateTime $datecreation = null, private ?int $id = null) {
        
    }

    public function getId() : ?int {
        return $this->id;
    }

    public function getContent() : string {
        return $this->content;
    }

    public function isValid() : bool {
        return $this->isValid;
    }

    public function getIdUser() : int {
        return $this->idUser;
    }

    public function getIdPost() : int {
        return $this->idPost;
    }

    public function getDateCreation() : ?DateTime {
        return $this->datecreation;
    }

    public function setDateCreation(DateTime $datecreation)
    {
        $this->datecreation = $datecreation;
    }
}