<?php

namespace Chayka\MVC;

use Chayka\Helpers\Util;
use \Exception;


class Application {

    protected $router;
    protected $controllers;
    protected $appPath;
    protected $appId;

    /**
     * Application constructor
     *
     * @param $appPath
     * @param string $appId
     */
    public function __construct($appPath, $appId=''){
        $this->appPath = $appPath;
        $this->appId = $appId;
        $this->router = new Router();
        $this->controllers = array();

        set_include_path($appPath.PATH_SEPARATOR.get_include_path());

        if(file_exists($appPath.'/autoload.php')){
            require_once $appPath.'/autoload.php';
        }
    }

    /**
     * Get router to setup routing
     *
     * @return Router
     */
    public function getRouter(){
        return $this->router;
    }

    /**
     * Dispatch requestUri (call the 'controller > action > view' chain)
     *
     * @param $requestUri
     * @return string
     * @throws Exception
     */
    public function dispatch($requestUri){
        $request = $this->router->parseRequest($requestUri);
        if($request){
            $controller = Util::getItem($request, 'controller');
            if($controller){
                $controllerClassname = Controller::path2controller($controller);
                $controllerSrc = $this->appPath.'/controllers/'.$controllerClassname.'.php';

                if(file_exists($controllerSrc)){
                    $controllerClassname1 = '\\'.$this->appId.'\\'.$controllerClassname;
                    $controllerClassname2 = $this->appId.'_'.$controllerClassname;
                    require_once $controllerSrc;
                    if(class_exists($controllerClassname1)){
                        $controllerObject = new $controllerClassname1($this->appPath);
                    }elseif(class_exists($controllerClassname2)){
                        $controllerObject = new $controllerClassname2($this->appPath);
                    }else{
                        throw new Exception('Controller not found', 0);
                    }
                    return $controllerObject->dispatch($request);
                }else{
                    throw new Exception('Controller not found', 0);
                }
            }
        }

        return '';

    }

}