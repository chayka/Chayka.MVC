<?php

namespace Chayka\MVC;

use Chayka\Helpers\Util;
use SebastianBergmann\Exporter\Exception;

class Router {

    protected $routes = array();

    /**
     * Add routes that can be served using our application.
     * Here are some samples
     * :controller/?action/*
     * my_controller/some_action/:some_part/*
     * my/act/?some/*
     *
     * @param string $label
     * @param string $urlPattern
     * @param array $defaults
     * @param array $paramPatterns
     * @return Router
     */
    public function addRoute($label = 'default', $urlPattern = '?controller/?action/*', $defaults = array('controller' => 'index', 'action'=>'index'), $paramPatterns = array()){
        $this->routes[$label] = array(
            'url' => explode('/', $urlPattern),
            'defaults' => $defaults,
            'params' =>$paramPatterns
        );
        return $this;
    }

    /**
     * Parse request URI and return input assoc array
     *
     * @param $requestUri
     * @return array
     */
    public function parseRequest($requestUri){
        $label = $this->matchRoute($requestUri);
        if($label){
            $route = Util::getItem($this->routes, $label);
            if($route){
                return $this->parseRoute($requestUri, $route);
            }
        }
        return null;
    }

    /**
     * Find the most matching route and return it's label
     *
     * @param $requestUri
     * @return string
     */
    public function matchRoute($requestUri){
        $max = 0;
        $res = null;
        foreach($this->routes as $label => $route){
            $strength = $this->compareRoute($requestUri, $route);
            if($strength > $max){
                $max = $strength;
                $res = $label;
            }
        }
        return $res;
    }

    /**
     * Compare request URI against provided route object and return it's strength
     *
     * @param $requestUri
     * @param $route
     * @return int
     */
    public function compareRoute($requestUri, $route){
        $patternParts = Util::getItem($route, 'url');
        $paramPatterns = Util::getItem($route, 'params');

        $url = parse_url('http://a.com'.$requestUri);
        $path = Util::getItem($url, 'path');
        $path = preg_replace('%[//]+$%', '', substr($path, 1));

        $pathParts = explode('/', $path);

        $strength = 0;

        $optional = 0;
        foreach($patternParts as $i=>$part){
            if(preg_match('%^\?%', $part)){
	            /**
	             * '?some-param' means optional param
	             */
                if(isset($pathParts[$i])){
                    $strength++;
                    $param = substr($part, 1);
                    $paramPattern = Util::getItem($paramPatterns, $param);
                    if($paramPattern){
                        $value = urldecode($pathParts[$i]);
                        if(!preg_match($paramPattern, $value)){
                            return 0;
                        }
                    }
                }
                $optional++;
            }elseif(preg_match('%^:%', $part)){
	            /**
	             * ':some-param' means required param
	             */
                if(isset($pathParts[$i])){
                    $strength++;
                    $param = substr($part, 1);
                    $paramPattern = Util::getItem($paramPatterns, $param);
                    if($paramPattern){
                        $value = urldecode($pathParts[$i]);
                        if(!preg_match($paramPattern, $value)){
                            return 0;
                        }
                    }
                }else{
                    return 0;
                }
            }elseif($part == '*' && count($pathParts)>=$i-$optional){
	            /**
	             * '*' means any sequence of '/param1/value1/param2/value'
	             */
                $strength++;
            }else{
	            /**
	             * Url parts exact match check
	             */
                if(isset($pathParts[$i]) && $part == $pathParts[$i]){
                    $strength+=10;
                }else{
                    return 0;
                }
            }
        }
        return $strength;
    }

    /**
     * Parse request URI against provided route object
     *
     * @param string $requestUri
     * @param array $route
     * @return array
     */
    public function parseRoute($requestUri, $route){
        $patternParts = Util::getItem($route, 'url');

        $url = parse_url('http://a.com'.$requestUri);
        $path = Util::getItem($url, 'path');
        $query = Util::getItem($url, 'query');
        $path = substr($path, 1);

        $pathParts = explode('/', $path);

        $strength = 0;

        $optional = 0;

        $param = '';

        $res = Util::getItem($route, 'defaults');
        foreach($pathParts as $i=>$pathPart){
            $part = Util::getItem($patternParts, $i);
            if($part && $part !=='*'){
                if(preg_match('%^\?%', $part)){
	                /**
	                 * '?some-param' means optional param
	                 */
                    if(isset($pathParts[$i])){
                        $strength++;
                        $param = substr($part, 1);
                        $value = urldecode($pathPart);
                        if($value){
                            $res[$param] = $value;
                        }
                        $param = '';
                    }
                    $optional++;
                }elseif(preg_match('%^:%', $part)){
	                /**
	                 * ':some-param' means required param
	                 */
                    if(isset($pathParts[$i])){
                        $strength++;
                        $param = substr($part, 1);
                        $value = urldecode($pathPart);
                        if($value){
                            $res[$param] = $value;
                        }
                        $param = '';
                    }
                }

            }else{
	            /**
	             * capture 'param1/value1/param2/value2' sequence for '*'
	             */
                if(!$param){
	                /**
	                 * capture param name
	                 */
                    $param = $pathPart;
                }else{
	                /**
	                 * capture param value
	                 */
                    $value = urldecode($pathPart);
                    if($value){
                        $res[$param] = $value;
                    }
                    $param = '';
                }
            }
        }
        parse_str($query, $queryParams);
        if($queryParams){
            foreach($queryParams as $param => $value){
                if($value){
                    $res[$param]=$value;
                }
            }
        }
        return $res;
    }

	public function assembleRoute($params = [], $label = 'default'){
		$route = Util::getItem($this->routes, $label);
//		Util::print_r($route);
		$patternParts = Util::getItem($route, 'url', []);
		$defaults = Util::getItem($route, 'defaults', []);
		$params = array_merge($defaults, $params);
//
//		$url = parse_url('http://a.com'.$requestUri);
//		$path = Util::getItem($url, 'path');
//		$query = Util::getItem($url, 'query');
//		$path = substr($path, 1);
//
//		$pathParts = explode('/', $path);
//
//		$strength = 0;
//
//		$optional = 0;
//
//		$param = '';
//
		$res = [];
		foreach($patternParts as $i=>$part){
			if($part !=='*'){
				if(preg_match('%^\?%', $part)){
					/**
					 * '?some-param' means optional param
					 */
					$param = substr($part, 1);
					if(isset($params[$param])){
						$value = urlencode($params[$param]);
						if(strlen($value)){
							$res[]=[
								'value'=> '/'.$value,
								'default' => $params[$param] == Util::getItem($defaults, $param),
							];
						}
						unset($params[$param]);
					}
				}elseif(preg_match('%^:%', $part)){
					/**
					 * ':some-param' means required param
					 */
					$param = substr($part, 1);
					if(isset($params[$param])){
						$value = urlencode($params[$param]);
						if(strlen($value)){
							$res[]=[
								'value'=> '/'.$value,
								'default' => $params[$param] == Util::getItem($defaults, $param),
							];
						}
						unset($params[$param]);
					}else{
						throw new Exception('Required parameter ['.$param.'] not set');
					}
				}else{
					$res[]=[
						'value'=> '/'.$part,
						'default' => false,
					];
				}

			}else{
				/**
				 * capture 'param1/value1/param2/value2' sequence for '*'
				 */
				unset($params['controller']);
				unset($params['action']);
				foreach($params as $param => $value){
					if(strlen($value)){
						$res[]=[
							'value'=> '/'.urlencode($param).'/'.urlencode($value),
							'default' => $params[$param] == Util::getItem($defaults, $param),
						];
					}
				}
			}
		}

		$url = '';
		$canOmit = true;
		for($i = count($res)-1; $i>=0; $i--){
			$part = $res[$i]['value'];
			$def = $res[$i]['default'];
			if(!$def){
				$canOmit = false;
			}
			if(!$canOmit){
				$url = $part.$url;
			}
		}
		return $url?$url:'/';

	}
} 