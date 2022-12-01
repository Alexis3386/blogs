<?php

namespace App\Framework\Http;

class Request {
    public function __construct(
        private string $path,
        private array $get,
        private array $post,
        private array $cookie,
    )
    {

    }

    public function getPath() {
        return $this->path;
    }

    public function getGet() {
        return $this->get;
    }

    public function getPost() {
        return $this->post;
    }

    public function getCookie() {
        return $this->cookie;
    } 
}
