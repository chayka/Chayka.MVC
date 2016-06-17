<?php
/**
 * Chayka.Framework is a framework that enables WordPress development in a MVC/OOP way.
 *
 * More info: https://github.com/chayka/Chayka.Framework
 */

namespace Chayka\MVC;

use Chayka\Helpers\Util;
use Chayka\Helpers\InputHelper;

/**
 * Class Controller is a base class for all the controllers in MVC pattern.
 * All the requests are routed to it's descendants that should implement at least one
 * <actionName>Action method.
 *
 * For example: /cars/add request is routed to CarsController->addAction().
 *
 * CarsController should be a descendant of Controller.
 *
 * @package Chayka\MVC
 */
abstract class Controller {
    /**
     * @var View object for current request.
     */
    protected $view;

    /**
     * Instance of the Application that contains controller.
     * @var Application
     */
	protected $application;

    /**
     * Container for the forwarded requests.
     * @var null
     */
	protected $forwardedRequest = null;

    /**
     * Controller constructor
     *
     * @param Application $application
     */
    public function __construct($application){
	    $this->application = $application;
        $this->view = new View();
        $this->view->addBasePath($application->getPath().'/views/');
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
	 * @param array $request
	 * @param View $forwardedView
	 *
	 * @return null|string
	 * @throws \Exception
	 */
    public function dispatch($request, $forwardedView = null){
	    $cls = get_class($this);
	    $clsParts = explode('\\', $cls);
	    $thisController = self::controller2path(end($clsParts));
        $controller = Util::getItem($request, 'controller', $thisController);
	    if($controller != $thisController){
		    return $this->getApplication()->dispatch($request, false, $forwardedView);
	    }
        $action = Util::getItem($request, 'action');
        $callback = self::path2action($action);
        if(method_exists($this, $callback)){
	        if($forwardedView){
		        $this->view = $forwardedView;
	        }
            InputHelper::setParams($request);
            ob_start();
            $this->init();
            $blockRender = call_user_func(array($this, $callback));
            $res = ob_get_clean();
	        if($this->forwardedRequest){
		        $request = $this->forwardedRequest;
		        $this->forwardedRequest = null;
		        $res.= $this->dispatch($request, $this->view);
		        $blockRender = true;
	        }
            return $blockRender? $res : $res.$this->view->render($controller.'/'.$action.'.phtml');
        }else{
            ob_end_clean();
            throw new \Exception("Action [$callback] not found", 0);
        }

//        return null;
    }

	/**
	 * After dispatching current action forwards processing to specified action controller.
	 *
	 * @param $action
	 * @param string $controller
	 *
	 * @return bool
	 */
	public function forward($action, $controller = ''){
		$params = InputHelper::getParams(false);
		$params['action'] = $action;
		if($controller){
			$params['controller'] = $controller;
		}
		$this->forwardedRequest = $params;
		return true;
	}

	/**
     * Get Application instance
     *
	 * @return Application
	 */
	public function getApplication() {
		return $this->application;
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

    /**
     * Get Pagination singleton instance
     *
     * @param $templatePath
     * @return Pagination
     */
    public function getPagination($templatePath = null){
        $pagination = Pagination::getInstance();
        if($templatePath) {
            $pagination->setViewTemplate($templatePath);
        }
        return $pagination;
    }
} 