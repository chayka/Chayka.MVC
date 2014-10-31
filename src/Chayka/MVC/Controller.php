<?php

namespace Chayka\MVC;

use Chayka\Helpers\Util;
use Chayka\Helpers\InputHelper;

abstract class Controller {

    protected $view;
    protected $appPath;

    /**
     * Controller constructor
     *
     * @param $appPath
     */
    public function __construct($appPath){
        $this->appPath = $appPath;
        $this->view = new View();
        $this->view->addBasePath($this->appPath.'/views/');
    }


    /**
     * This function is called before action is dispatched.
     * You can override it to create some custom initialization
     */
    public function init(){

    }

    /**
     * Dispatch request.
     * Request is formed by Application.
     * This function finds appropriate action callback and invokes it
     *
     * @param $request
     * @return null|string
     * @throws \Exception
     */
    public function dispatch($request){
        $controller = Util::getItem($request, 'controller');
        $action = Util::getItem($request, 'action');
        $callback = self::path2action($action);
        if(method_exists($this, $callback)){
            InputHelper::setInput($request);
            ob_start();
            $this->init();
            call_user_func(array($this, $callback));
            $res = ob_get_clean();
            return $res.$this->view->render($controller.'/'.$action.'.phtml');
        }else{
            throw new \Exception('Action not found', 0);
        }

//        return null;
    }

    /**
     * Convert action path string to callback name
     *
     * @param $path
     * @return string
     */
    public static function path2action($path){
        $path = strtolower($path);
        $parts = preg_split('%[^\w\d]+%u', $path, -1, PREG_SPLIT_NO_EMPTY);
        $action = '';
        foreach($parts as $i=>$part){
            $action.= $i?ucfirst($part):$part;
        }
        return $action.'Action';
    }

    /**
     * Convert controller path string to controller classname
     *
     * @param $path
     * @return string
     */
    public static function path2controller($path){
        $path = strtolower($path);
        $parts = preg_split('%[^\w\d]+%u', $path, -1, PREG_SPLIT_NO_EMPTY);
        $controller = '';
        foreach($parts as $i=>$part){
            $controller.= ucfirst($part);
        }
        return $controller.'Controller';
    }

    /**
     * Convert action callback name to path string
     *
     * @param $action
     * @return string
     */
    public static function action2path($action){
        $action = preg_replace('%(Action|Controller)$%u', '', $action);
        $action = ucfirst($action);
        preg_match_all('%[A-Z][a-z\d]*%u', $action, $m);
        $res = array();
        foreach($m[0] as $word){
            $res[]=strtolower($word);
        }
        return join('-', $res);
    }

    /**
     * Convert controller classname to path string
     *
     * @param $controller
     * @return string
     */
    public static function controller2path($controller){
        return self::action2path($controller);
    }
} 