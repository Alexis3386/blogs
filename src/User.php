<?php


namespace App;


class User
{
    private int $id;
    private string $email;
    private string $pseudo;
    private string $username;
    private bool $isadmin;

    public function __construct($id, $email, $pseudo, $username, $isadmin)
    {
        $this->id = $id;
        $this->email = $email;
        $this->pseudo = $pseudo;
        $this->username = $username;
        $this->isadmin = $isadmin;
    }

    /**
     * @return bool
     */
    public function isadmin() : bool {
        return $this->isadmin;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getPseudo(): string {
        return $this->pseudo;
    }
}