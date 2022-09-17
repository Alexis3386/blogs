<?php


namespace App;

use PDO;
use App\Blogpost;

class BlogpostRepository
{
    public function __construct(private PDO $pdo)
    {
    }
}