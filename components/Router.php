<?php

/**
 *
 */
class Router
{

    private $routes;

    /**
     * @return mixed
     */
    public function __construct()
    {
       $routePath=ROOT . '/config/routes.php';
       $this->routes =include($routePath);

    }

    private function getUri()
    {
        if (!empty($_SERVER['REQUEST_URI'])) {
            //for deployment return trim($_SERVER['REQUEST_URI'], '/');

            return substr($_SERVER['REQUEST_URI'], strlen('/mvc.shop/'));
        }
    }

    /**
     * @return mixed
     */
    public function run()
    {
        $uri=$this->getUri();

       foreach ($this->routes as $uriPattern=>$path) {
           if (preg_match( "~$uriPattern~",$uri)) {
              $segments=explode('/',$path);

               $controllerName=array_shift($segments) . 'Controller';
               $controllerName=ucfirst($controllerName);
               $actionName='action'. ucfirst(array_shift($segments));
               $controllerFile=ROOT . '/controllers/' . $controllerName . '.php';
               if (file_exists($controllerFile)) {
                   include_once $controllerFile;
               }

               $controllerObject=new $controllerName;
               $result=$controllerObject->$actionName();
               if ($result !=null) {
                   break;
               }

           }
       }
    }

}