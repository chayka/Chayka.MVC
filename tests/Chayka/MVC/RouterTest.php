<?php

use Chayka\MVC\Router;
class RouterTest extends PHPUnit_Framework_TestCase {

    public function testParsing(){
        $router = new Router();

        $router->addRoute('default', '?controller/?action/*', array('controller'=>'index', 'action'=>'index'));
        $router->addRoute('cars', 'sport/racing/?car/*', array('controller'=>'sport', 'action'=>'cars'));

        $this->assertEquals('default', $router->matchRoute('/'));
        $this->assertEquals('default', $router->matchRoute('/index'));
        $this->assertEquals('default', $router->matchRoute('/index/'));
        $this->assertEquals('default', $router->matchRoute('/index/index'));
        $this->assertEquals('default', $router->matchRoute('/index/index/'));
        $this->assertEquals(array('controller'=>'index', 'action'=>'index'), $router->parseRequest('/'));
        $this->assertEquals(array('controller'=>'sport', 'action'=>'index'), $router->parseRequest('/sport'));
        $this->assertEquals(array('controller'=>'sport', 'action'=>'cars'), $router->parseRequest('/sport/cars'));
        $this->assertEquals(array('controller'=>'sport', 'action'=>'cars'), $router->parseRequest('/sport/cars/'));
        $this->assertEquals(array('controller'=>'sport', 'action'=>'cars', 'car'=>'mazda'), $router->parseRequest('/sport/cars/car/mazda'));
        $this->assertEquals(array('controller'=>'sport', 'action'=>'cars', 'car'=>'mazda', 'model' => '6'), $router->parseRequest('/sport/racing/mazda/model/6'));
        $this->assertEquals(array('controller'=>'index', 'action'=>'index', 'a'=>'1', 'b'=>'hit me'), $router->parseRequest('/?a=1&b=hit%20me'));
    }

	public function testBuilding(){
		$router = new Router();

		$router->addRoute('default', '?controller/?action/*', array('controller'=>'index', 'action'=>'index'));
		$router->addRoute('cars', 'sport/racing/?car/*', array('controller'=>'sport', 'action'=>'cars'));

		$this->assertEquals('/', $router->assembleRoute());
		$this->assertEquals('/', $router->assembleRoute([]));
		$this->assertEquals('/', $router->assembleRoute(['controller' => '', 'action' =>''], 'default'));
		$this->assertEquals('/', $router->assembleRoute(['controller' => 'index', 'action' =>''], ''));
		$this->assertEquals('/', $router->assembleRoute(['controller' => 'index', 'action' =>'index'], ''));
		$this->assertEquals('/', $router->assembleRoute(['controller' => 'index', 'action' =>'index'], 'default'));
		$this->assertEquals('/sport', $router->assembleRoute(['controller'=>'sport', 'action'=>'index']));
		$this->assertEquals('/sport/cars', $router->assembleRoute(['controller'=>'sport', 'action'=>'cars']));
		$this->assertEquals('/sport/cars/car/mazda', $router->assembleRoute(['controller'=>'sport', 'action'=>'cars', 'car'=>'mazda']));
		$this->assertEquals('/sport/cars/car/mazda/model/6', $router->assembleRoute(['controller'=>'sport', 'action'=>'cars', 'car'=>'mazda', 'model' => '6']));
		$this->assertEquals('/sport/racing/mazda/model/6', $router->assembleRoute(['car'=>'mazda', 'model' => '6'], 'cars'));
	}
}
 