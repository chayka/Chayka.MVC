<?php

namespace Chayka\MVC;

use Chayka\Helpers\FsHelper;
use Chayka\Helpers\NlsHelper;
use Chayka\Helpers\Util;

class View {

    protected $vars = array();
    protected $basePaths = array('');
    protected $path = '';
    protected $nls = false;

    /**
     * Declare defaults var values, can be called
     *
     * @param $vars
     */
    public function declareVars($vars){
        foreach($vars as $key=>$val){
            if(!isset($this->vars[$key])){
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
            if($this->nls){
                $fn_ = FsHelper::setExtensionPrefix($fn, '_');
                $fnLang = FsHelper::setExtensionPrefix($fn, NlsHelper::getLang());
                if(file_exists($fnLang)){
                    $fn = $fnLang;
                }else if(file_exists($fn_)){
                    $fn = $fn_;
                }
            }else if(!file_exists($fn)){
                $fn = '';
            }
            if($fn){
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
    public function partial($path, $vars = array()){
        $view = new self();
        foreach($this->basePaths as $basePath){
            $view->addBasePath($basePath);
        }
        $view->declareVars($vars);

        return $view->render($path);
    }

    /**
     * If turned on, when rendered, view will search localized version of template.
     * E.g. 'auth.en.phtml' if 'auth.phtml' requested.
     *
     * @param bool $enable
     */
    public function enableNls($enable = true){
        $this->nls = $enable;
    }

    /**
     * Get localized value, or value itself if localization is not found
     * This function can get multiple args and work like sprintf($template, $arg1, ... $argN)
     * Hint: Use $format = 'На %2$s сидят %1$d обезьян';
     *
     * @param string $value String to translate
     * @return string
     */
    public static function _($value) {
        if(func_num_args()>1){
            $args = func_get_args();
            $args[0] = NlsHelper::translate($value);
            return call_user_func_array('sprintf', $args);
        }
        return NlsHelper::translate($value);
    }

    /**
     * Echo localized value, or value itself if localization is not found
     * This function can get multiple args and work like sprintf($template, $arg1, ... $argN)
     * Hint: Use $format = 'На %2$s сидят %1$d обезьян';
     *
     * @param string $value String to translate
     * @return string
     */
    public static function __($value){
        if(func_num_args()>1){
            $args = func_get_args();
            $args[0] = NlsHelper::translate($value);
            echo $res = call_user_func_array('sprintf', $args);
            return $res;
        }
        echo $res = NlsHelper::translate($value);
        return $res;
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