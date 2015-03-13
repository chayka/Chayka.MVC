<?php

namespace Chayka\MVC;

use Chayka\Helpers\FsHelper;
use Chayka\Helpers\HtmlHelper;
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
	 * @param bool $output
	 *
	 * @return string
	 */
    public function escape($value, $output = true){
        $res = htmlentities($value);
	    if($output){
		    echo $res;
	    }
	    return $res;
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
     * @param string $path
     * @param array $vars
     * @return null|string
     */
    public function render($path, $vars = array()){

        if($vars && is_array($vars) && count($vars)){
            $this->declareVars($vars);
        }

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
            }
            if(!file_exists($fn)){
                $fn = '';
            }
            if($fn){
                ob_start();
                require($fn);
                $res = ob_get_clean();
                return $res;
            }
        }

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

        return $view->render($path, $vars);
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

    /**
     * Transform key=>value array to key="value" attributes string
     *
     * @param array $attributes
     * @return string
     */
    public function htmlAttributes($attributes){
        $str = '';
        foreach($attributes as $key => $value){
            $str.= sprintf(' %s="%s"', $key, htmlentities($value));
        }

        return trim($str);
    }

    /**
     * Generate html input element.
     *
     * @param string $name
     * @param string $value
     * @param string string $type
     * @param array $attributes
     * @return string
     */
    public function formInput($name, $value, $type = "text", $attributes = array()){
        return sprintf('<input type="%s" name="%s" value="%s"%s/>', $type, $name, $value, $this->htmlAttributes($attributes));
    }

    /**
     * Generate html input[type=checkbox] element.
     *
     * @param string $name
     * @param string $value
     * @param string $checked
     * @param string $unchecked
     * @param array $attributes
     * @return string
     */
    public function formCheckbox($name, $value, $checked = "1", $unchecked = "0", $attributes = array()){
        if($value === $checked){
            $attributes['checked'] = "checked";
        }
        return $this->formInput($name, $unchecked, 'hidden', array())
              .$this->formInput($name, $checked, 'checkbox', $attributes);
    }

    /**
     * Generate set of html input[type=radio] elements.
     *
     * @param string $name
     * @param string $value
     * @param array $options
     * @param array $attributes
     * @param string $separator
     * @return string
     */
    public function formRadio($name, $value, $options, $attributes=array(), $separator=' '){
        $res = '';
        foreach($options as $val => $label){
            $attrs = $attributes;
            if($value === $val){
                $attrs['checked'] = "checked";
            }
            $res.=sprintf('<label>%s %s</label>%s', $this->formInput($name, $val, 'radio', $attrs), $label, $separator);
        }
        return $res;
    }

    /**
     * Generate html select element.
     *
     * @param string $name
     * @param string $value
     * @param array $options
     * @param array $attributes
     * @return string
     */
    public function formSelect($name, $value, $options = array(), $attributes = array()){
        $htmlOptions = '';
        foreach($options as $val => $label){
            $selected = $value == $val?' selected="selected"':'';
            $htmlOptions.=sprintf('<option value="%s"%s>%s</option>', $val, $selected, $label);
        }
        return sprintf('<select name="%s"%s>%s</select>', $name, $this->htmlAttributes($attributes), $htmlOptions);
    }

    /**
     * Generate html textarea element.
     *
     * @param $name
     * @param $value
     * @param $attributes
     * @return string
     */
    public function formTextarea($name, $value, $attributes = array()){
        return sprintf('<textarea name="%s"%s>%s</textarea>', $name, $this->htmlAttributes($attributes), $value);
    }

    /**
     * Output 'style="display: none;"' if $condition truthy
     *
     * @param bool $condition
     */
    public static function hidden($condition = true){
        HtmlHelper::hidden($condition);
    }

    /**
     * Output 'style="display: none;"' if $condition truthy
     *
     * @param bool $condition
     */
    public static function visible($condition = true){
        HtmlHelper::visible($condition);
    }

    /**
     * Output 'checked="checked"' if $condition truthy
     *
     * @param bool $condition
     */
    public static function checked($condition = true){
        HtmlHelper::checked($condition);
    }

    /**
     * Output 'disabled="disabled"' if $condition truthy
     *
     * @param bool $condition
     */
    public static function disabled($condition = true){
        HtmlHelper::disabled($condition);
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