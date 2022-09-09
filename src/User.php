<?php


namespace App;


class User
{


    public function __construct(private int  $id, private string $email, private string $pseudo, private string $username,
                                private bool $isadmin)
    {
    }

    /**
     * @return bool
     */
    public function isadmin(): bool
    {
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
    public function getPseudo(): string
    {
        return $this->pseudo;
    }

    /**
     * @return String
     */
    public function getEmail(): string
    {
        return $this->email;
    }
}