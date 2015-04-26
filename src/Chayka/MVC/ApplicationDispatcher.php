<?php

namespace Chayka\MVC;

use Chayka\Helpers\Util;


class ApplicationDispatcher {
    protected static $applications = array();
    protected static $routes = array();
    protected static $forbiddenRoutes = array();
    protected static $notFound = false;
    protected static $isMainPage = false;

    /**
     * Register MVC Application that will serve requests
     *
     * @param Application $app
     * @param array(string) $routes
     */
    public static function registerApplication($app, $routes){
	    $id = $app->getId();
        self::$applications[$id] = array(
            'app' => $app,
            'routes' => $routes,
        );

        foreach($routes as $route){
            self::$routes[$route] = $id;
        }
    }

    /**
     * Forbid route by preg mask
     *
     * @param string $mask
     * @param string $maskId
     */
    public static function forbidRoute($mask, $maskId = null){
        if($maskId){
            self::$forbiddenRoutes[$maskId] = $mask;
        }else{
            self::$forbiddenRoutes[] = $mask;
        }
    }

    /**
     * Forbid several routes by preg masks
     *
     * @param array(string) $routes
     */
    public static function forbidRoutes($routes){
        foreach($routes as $id=>$mask){
            if(is_numeric($id)){
                self::forbidRoute($mask);
            }else{
                self::forbidRoute($mask, $id);
            }
        }
    }

    /**
     * Check request uri if it is forbidden
     *
     * @param $requestUri
     * @return bool
     */
    public static function isForbiddenRoute($requestUri){
        $requestUri = preg_replace('%^\/(api)?\/?%', '', $requestUri);
        foreach(self::$forbiddenRoutes as $mask){
            if(preg_match($mask, $requestUri)){
                return true;
            }
        }
        return false;
    }

    /**
     * Check if uri can be processed
     *
     * @param $requestUri
     * @return int
     */
    public static function canProcess($requestUri){
        if(empty($requestUri) || '/'==$requestUri){
            $requestUri = '/index/';
        }
        return preg_match('%^\/((api)\/)?('.  join('|', array_keys(self::$routes)).')(\/|\z)%',$requestUri, $m)
            && !self::isForbiddenRoute($requestUri);
    }

    /**
     * Dispatch $uri and return rendered response.
     * If no route found or route is forbidden returns null.
     *
     * @param null $uri
     * @param null $appId
     * @return null
     */
    public static function dispatch($uri = null, $appId = null){
        if($uri === null){
            $uri = $_SERVER['REQUEST_URI'];
        }
        if(!self::canProcess($uri) || self::isForbiddenRoute($uri)){
            return null;
        }
        $tmpUri = $_SERVER['REQUEST_URI'];
        $_SERVER['REQUEST_URI'] = $uri;
        $uri = preg_replace('%^\/api%', '', $uri);
        if(!$appId){
            $route = preg_match('%^\/([^\/]+)%', $uri, $m)?$m[1]:'index';
            $appId = Util::getItem(self::$routes, $route);
        }
        if($appId){

            $appInfo = Util::getItem(self::$applications, $appId);

            $app = Util::getItem($appInfo, 'app');

            $r = $app->dispatch($uri);

            $_SERVER['REQUEST_URI'] = $tmpUri;

            return $r;
        }

        return null;

    }


}