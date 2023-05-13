<?php

//url = controller/method/param[]
//Core class

class Core
{
    private $controller = 'pages';
    private $method = 'document';
    private $param = [];

    public function __construct()
    {

        $url = $this->getUrl();

		unset($url[0],$url[1],$url[2]);

        if (isset($url[3])) {

            if (file_exists(dirname(__DIR__).'/controllers/' . ucwords($url[3]) . '.class.php')) {
                $this->controller = ucwords($url[3]);
                unset($url[3]);
            }
        }

        // Require The Controller
        require_once dirname(__DIR__)."/controllers/" . $this->controller . ".class.php" ;
        //Instantiation Of controller
        $this->controller = new $this->controller;

        if (isset($url[4])) {
            if (method_exists($this->controller, $url[4])) {
                $this->method = $url[4];
                unset($url[4]);
            }
        }

        $this->param = $url ? array_values($url) : []; //Ternary Operator

        //Call The Function
        call_user_func_array([$this->controller, $this->method], $this->param);
    }

    public function getUrl()
    {
        if (isset($_SERVER['REQUEST_URI'])) {
            $url = $_SERVER['REQUEST_URI'];
            $url = filter_var($url, 518); //FILTER_SANITIZE_URL
            $url = rtrim($url, '/'); // To Remove '/'
            $url = explode('/', $url);
            return $url;
        }
    }
}
