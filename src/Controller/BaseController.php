<?php

namespace App\Controller;

class BaseController
{
    private $_httpRequest;
    private $_param;
    private $_config;
    protected array $_manager;

    public function __construct($httpRequest, $config)
    {
        $this->_httpRequest = $httpRequest;
        $this->_config = $config;
        $this->_param = array();
        $this->addParam("httprequest", $this->_httpRequest);
        $this->addParam("config", $this->_config);
        $this->bindManager();
    }

    protected function view($template, array $parametres = []): void
    {

        $loader = new \Twig\Loader\FilesystemLoader('templates');
        $twig = new \Twig\Environment($loader, [
            'cache' => false,
            'debug' => true
        ]);

        global $user, $user_connecte, $categories;
        $defaultParam = [
            'user_connecte' => $user_connecte,
            'user' => $user,
            'categories' => $categories,
        ];
        if (isset($_SESSION['notification'])) {
            $defaultParam['notification'] = $_SESSION['notification'];
        }
        echo $twig->render($template, array_merge($defaultParam, $parametres));
        unset($_SESSION['notification']);
    }

    public function bindManager(): void
    {
        foreach($this->_httpRequest->getRoute()->getManager() as $manager)
        {
            $manager = 'App\\Model\\Manager\\'.$manager;
            $this->_manager[$manager] = new $manager($this->_config->database);
        }
    }

    public function addParam($name, $value): void
    {
        $this->_param[$name] = $value;
    }
}
