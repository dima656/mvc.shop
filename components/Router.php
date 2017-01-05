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

               echo '<br>what user are lokking for ' . $uri;
               echo '<br>matches from rules ' . $uriPattern;
               echo '<br>who work with ' . $path;

               //receive internal route from external according to rules
               $internalRoute=preg_replace("~$uriPattern~",$path,$uri);
               echo '<br>need to be created ' . $internalRoute;

              $segments=explode('/',$internalRoute);

               $controllerName=array_shift($segments) . 'Controller';
               $controllerName=ucfirst($controllerName);
               $actionName='action'. ucfirst(array_shift($segments));
               $parameters=$segments;
               echo $controllerName;
               echo $actionName;
               print_r($parameters);
               $controllerFile=ROOT . '/controllers/' . $controllerName . '.php';
               if (file_exists($controllerFile)) {
                   include_once $controllerFile;
               }

               $controllerObject=new $controllerName;
               $result=call_user_func_array([$controllerObject,$actionName],$parameters);
              // $result=$controllerObject->$actionName($parameters);
               if ($result !=null) {
                   break;
               }

           }
       }
    }

}