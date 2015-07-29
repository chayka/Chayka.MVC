Chayka\MVC\Controller
===============

Class Controller is a base class for all the controllers in MVC pattern.

All the requests are routed to it's descendants that should implement at least one
<actionName>Action method.

For example: /cars/add request is routed to CarsController->addAction().

CarsController should be a descendant of Controller.


* Class name: Controller
* Namespace: Chayka\MVC
* This is an **abstract** class





Properties
----------


### $view

    protected \Chayka\MVC\View $view





* Visibility: **protected**


### $application

    protected \Chayka\MVC\Application $application

Instance of the Application that contains controller.



* Visibility: **protected**


### $forwardedRequest

    protected null $forwardedRequest = null

Container for the forwarded requests.



* Visibility: **protected**


Methods
-------


### __construct

    mixed Chayka\MVC\Controller::__construct(\Chayka\MVC\Application $application)

Controller constructor



* Visibility: **public**


#### Arguments
* $application **[Chayka\MVC\Application](Chayka-MVC-Application.md)**



### init

    mixed Chayka\MVC\Controller::init()

This function is called before action is dispatched.

You can override it to create some custom initialization

* Visibility: **public**




### dispatch

    null|string Chayka\MVC\Controller::dispatch(array $request, \Chayka\MVC\View $forwardedView)

Dispatch request.

Request is formed by Application.
This function finds appropriate action callback and invokes it

* Visibility: **public**


#### Arguments
* $request **array**
* $forwardedView **[Chayka\MVC\View](Chayka-MVC-View.md)**



### forward

    boolean Chayka\MVC\Controller::forward($action, string $controller)

After dispatching current action forwards processing to specified action controller.



* Visibility: **public**


#### Arguments
* $action **mixed**
* $controller **string**



### getApplication

    \Chayka\MVC\Application Chayka\MVC\Controller::getApplication()

Get Application instance



* Visibility: **public**




### path2action

    string Chayka\MVC\Controller::path2action($path)

Convert action path string to callback name



* Visibility: **public**
* This method is **static**.


#### Arguments
* $path **mixed**



### path2controller

    string Chayka\MVC\Controller::path2controller($path)

Convert controller path string to controller classname



* Visibility: **public**
* This method is **static**.


#### Arguments
* $path **mixed**



### action2path

    string Chayka\MVC\Controller::action2path($action)

Convert action callback name to path string



* Visibility: **public**
* This method is **static**.


#### Arguments
* $action **mixed**



### controller2path

    string Chayka\MVC\Controller::controller2path($controller)

Convert controller classname to path string



* Visibility: **public**
* This method is **static**.


#### Arguments
* $controller **mixed**



### getPagination

    \Chayka\MVC\Pagination Chayka\MVC\Controller::getPagination($templatePath)

Get Pagination singleton instance



* Visibility: **public**


#### Arguments
* $templatePath **mixed**


