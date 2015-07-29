<?php
/**
 * Chayka.Framework is a framework that enables WordPress development in a MVC/OOP way.
 *
 * More info: https://github.com/chayka/Chayka.Framework
 */

namespace Chayka\MVC;

use Chayka\Helpers\InputHelper;
use Chayka\Helpers\JsonHelper;
use Chayka\Helpers\Util;

/**
 * Class RestController extends Controller and provides routing to it's Actions
 * based on the type of HTTP request method.
 *
 * HTTP POST    -> createAction()   | postAction()
 * HTTP GET     -> readAction()     | getAction()
 * HTTP PUT     -> updateAction()   | putAction
 * HTTP DELETE  -> deleteAction()
 *
 * @package Chayka\MVC
 */
class RestController extends Controller {

    /**
     * This is a base init method for the controller is run always before Action processing.
     * When overriding RestController->init() mind to keep parent::init()
     * or at least InputHelper::captureInput() for the RestController magic to happen.
     */
	public function init(){
		InputHelper::captureInput();
	}

	/**
	 * Action when get root requested.
	 * Called to fetch list of entities
	 *
	 * @param bool $respond if true JSON wrapped entity will be responded
	 *
	 * @return null
	 */
	public function listAction($respond = true){
		if($respond){
			JsonHelper::respond();
		}
		return null;
	}

	/**
	 * Create entity by issue of POST request
	 *
	 * @param bool $respond if true JSON wrapped entity will be responded
	 *
	 * @return null
	 */
	public function createAction($respond = true){
		if($respond){
			JsonHelper::respond();
		}
		return null;
	}

	/**
	 * Read entity by issue GET request
	 *
	 * @param bool $respond
	 *
	 * @return null
	 */
	public function readAction($respond = true){
		if($respond){
			JsonHelper::respond();
		}
		return null;
	}

	/**
	 * Update entity by issue of PUT request
	 *
	 * @param bool $respond if true JSON wrapped entity will be responded
	 *
	 * @return null
	 */
	public function updateAction($respond = true){
		if($respond){
			JsonHelper::respond();
		}
		return null;
	}

	/**
	 * Delete entity by issue of DELETE request
	 *
	 * @param bool $respond if true JSON wrapped entity will be responded
	 *
	 * @return null
	 */
	public function deleteAction($respond = true){
		if($respond){
			JsonHelper::respond();
		}
		return null;
	}

	/**
	 * Alias of listAction - default action,
	 * both can be redefined
	 *
	 * @param bool $respond
	 *
	 * @return null
	 */
	public function indexAction($respond = true) {
		return $this->listAction($respond);
	}

	/**
	 * Alias of createAction, both can be redefined
	 *
	 * @param bool $respond
	 *
	 * @return null
	 */
	public function postAction($respond = true) {
		return $this->createAction($respond);
	}

	/**
	 * Alias of readAction, both can be redefined
	 *
	 * @param bool $respond
	 *
	 * @return null
	 */
	public function getAction($respond = true) {
		return $this->readAction($respond);
	}

	/**
	 * Alias of updateAction, both can be redefined
	 *
	 * @param bool $respond
	 *
	 * @return null
	 */
	public function putAction($respond = true) {
		return $this->updateAction($respond);
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
		if($action == 'REST'){
			$action = strtolower($_SERVER['REQUEST_METHOD']);
		}
		$callback = self::path2action($action);
		if(method_exists($this, $callback)){
			if($forwardedView){
				$this->view = $forwardedView;
			}
			InputHelper::setParams($request);
			ob_start();
			$this->init();
			call_user_func(array($this, $callback));
			$res = ob_get_clean();
			if($this->forwardedRequest){
				$request = $this->forwardedRequest;
				$this->forwardedRequest = null;
				$res.= $this->dispatch($request, $this->view);
			}
			return $res;
		}else{
			throw new \Exception("Action [$callback] not found", 0);
		}
	}

} 