<?php

namespace Chayka\MVC;

use Chayka\Helpers\Util;

class View {

    protected $vars = array();
    protected $basePaths = array('');
    protected $path = '';

    /**
     * Declare defaults var values, can be called
     *
     * @param $vars
     */
    public function defaultVars($vars){
        foreach($vars as $key=>$val){
            if(!is_set($this->vars[$key])){
                $this->vars[$key] = $val;
            }
        }
    }

    /**
     * Escape $value and output it;
     *
     * @param $value
     */
    public function escape($value){
        echo htmlentities($value);
    }

    /**
     * Add path to look for templates
     *
     * @param $basePath
     */
    public function addBasePath($basePath){
        if(strpos($basePath, '/')!=strlen($basePath)-1){
            $basePath.='/';
        }
        $this->basePaths[] = $basePath;
    }

    /**
     * Render .phtml template
     *
     * @param $path
     * @return null|string
     */
    public function render($path){
        foreach($this->basePaths as $base){
            $fn =$base.$path;
            if(file_exists($base.$path)){
                ob_start();
                require($fn);
                $res = ob_get_clean();
                return $res;
            }
        }
//        throw new Exception('View not found', 0);
        return null;
    }

    /**
     * Render template with defined scope
     *
     * @param $path
     * @param $vars
     * @return null|string
     */
    public function partial($path, $vars){
        $view = new self();
        foreach($this->basePaths as $basePath){
            $view->addBasePath($basePath);
        }
        $view->declareVars($vars);

        return $view->render($path);
    }

    /**
     * Assign template var.
     * $this->var = 'value' can be used.
     *
     * @param $key
     * @param $value
     */
    public function assign($key, $value){
        $this->vars[$key] = $value;
    }

    /**
     * Magic var setter
     *
     * @param $key
     * @param $value
     */
    public function __set($key, $value){
        $this->vars[$key] = $value;
    }


    /**
     * Magic var getter
     *
     * @param $key
     * @return mixed
     */
    public function __get($key){
        return Util::getItem($this->vars, $key);
    }
} 