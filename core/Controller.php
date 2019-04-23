<?php
class Controller
{
    
    protected $path;
    protected $mainView;
   // protected $admin;
    
    public function run()
    {
        $this->route();
        $this->action();
    }

    public function __construct() 
    {  
       $this->mainView = new View(ROOT."/modules/views/index.php");
    }

    /**
     * 
     */
    public function route()
    {
        // Чистим пустые Get
        if (!empty($_GET)) {
            $new_get = array_filter($_GET);
            if (count($new_get) < count($_GET)) {
                $request_uri = parse_url('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'], PHP_URL_PATH);
                header('Location: ' . $request_uri . '?' . http_build_query($new_get));
                exit;
            }
        }
        
        $uri_parts = explode('?', $_SERVER['REQUEST_URI'], 2);
        
        $routes = include(ROOT."/config/routes.php");
        $uri = trim($uri_parts[0], "/");
        $path = "home/page404";
        foreach($routes as $key => $route){
            if(preg_match("*^$key$*", $uri)){
                $path = preg_replace("*$key*", $route, $uri);
                break;
            }
        }
        $this->path = $path;
    }
    
    public function action() 
    {
        $parts = explode("/", $this->path);
        $module = array_shift($parts);
        $method = array_shift($parts);
        $parameters = $parts;
        $controller = ucfirst($module) . "Controller";
        $action = $method . "Action";
        if(class_exists($controller)) {
            $controllerObject = new $controller;
            if (method_exists($controllerObject, $action)){
                $controllerObject -> $action($parameters);
            }
        }
    }    
    
}
