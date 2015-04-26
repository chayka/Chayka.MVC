<?php

namespace Chayka\MVC;

use Chayka\Helpers\Util;
use \Exception;


class Application {

    protected $router;
    protected $controllers;
    protected $path;
    protected $id;

    /**
     * Application constructor
     *
     * @param $appPath
     * @param string $appId
     */
    public function __construct($appPath, $appId=''){
        $this->path = $appPath;
        $this->id = $appId;
        $this->router = new Router();
        $this->controllers = [];

        if(file_exists($appPath.'/autoload.php')){
            require_once $appPath.'/autoload.php';
        }
    }

	/**
	 * Get application id
	 *
	 * @return string
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * Get application path
	 *
	 * @return string
	 */
	public function getPath() {
		return $this->path;
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
	 * Get new or cached controller for classname
	 *
	 * @param string $className
	 * @return Controller
	 */
	public function getController($className, $newController = false ){
		if($newController || !isset($this->controllers[$className])){
			$this->controllers[$className] = new $className($this);
		}

		return $this->controllers[$className];
	}

	/**
	 * Dispatch requestUri (call the 'controller > action > view' chain)
	 * Cached controller will be used if available.
	 * Specify $newController = true if you need a clean one.
	 *
	 * @param string|array $request
	 * @param bool $newController
	 * @param View $forwardedView
	 *
	 * @return string
	 * @throws Exception
	 */
    public function dispatch($request, $newController = false, $forwardedView = null){
	    if(is_string($request)){
		    $request = $this->router->parseRequest($request);
	    }
        if($request){
            $controller = Util::getItem($request, 'controller');
            if($controller){
                $controllerClassname = Controller::path2controller($controller);
                $controllerSrc = $this->path.'/controllers/'.$controllerClassname.'.php';

                if(file_exists($controllerSrc)){
                    $controllerClassName1 = '\\'.$this->id.'\\'.$controllerClassname;
                    $controllerClassName2 = $this->id.'_'.$controllerClassname;
                    require_once $controllerSrc;
                    if(class_exists($controllerClassName1)){
                        $controllerObject = $this->getController($controllerClassName1, $newController);
                    }elseif(class_exists($controllerClassName2)){
                        $controllerObject = $this->getController($controllerClassName2, $newController);
                    }else{
                        throw new Exception("Controller class [$controllerClassName1 or $controllerClassName2] not found at [$controllerSrc]", 0);
                    }
                    return $controllerObject->dispatch($request, $forwardedView);
                }else{
                    throw new Exception("Controller file [$controllerSrc] not found", 0);
                }
            }
        }

        return '';

    }

}