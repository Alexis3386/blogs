<?php

namespace App\Controller;

class HomeController extends BaseController
{
    public function Home(): void
    {
        $categories = $this->_manager["App\Model\Manager\CategoryManager"]->getAllcategorie();
        $posts = $this->_manager["App\Model\Manager\BlogPostManager"]->findPostWithPagination(1);
        $this->view("home.twig", ['posts' => $posts, 'categories' => $categories]);
    }
}
