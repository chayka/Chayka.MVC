<?php

use Chayka\MVC\Controller;

class ControllerTest extends PHPUnit_Framework_TestCase {

    public function testPath2controller(){
        $this->assertEquals('MyController', Controller::path2controller('my'));
        $this->assertEquals('MyCoolController', Controller::path2controller('my-cool'));
    }

    public function testPath2action(){
        $this->assertEquals('myAction', Controller::path2action('my'));
        $this->assertEquals('myCoolAction', Controller::path2action('my-cool'));
    }

    public function testController2path(){
        $this->assertEquals('my', Controller::controller2path('MyController'));
        $this->assertEquals('my-cool', Controller::controller2path('MyCoolController'));
    }

    public function testAction2path(){
        $this->assertEquals('my', Controller::action2path('myAction'));
        $this->assertEquals('my-cool', Controller::action2path('myCoolAction'));
    }

}
 