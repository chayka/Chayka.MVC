Chayka\MVC\Application
===============

Instance of Application class is a managing object for MVC application instance




* Class name: Application
* Namespace: Chayka\MVC





Properties
----------


### $router

    protected \Chayka\MVC\Router $router

Router that dispatches request to the controller based on the existing routes.



* Visibility: **protected**


### $controllers

    protected array $controllers

Cache for initialized controllers



* Visibility: **protected**


### $path

    protected string $path

Application base path



* Visibility: **protected**


### $id

    protected string $id

Application id



* Visibility: **protected**


Methods
-------


### __construct

    mixed Chayka\MVC\Application::__construct($appPath, string $appId)

Application constructor



* Visibility: **public**


#### Arguments
* $appPath **mixed**
* $appId **string**



### getId

    string Chayka\MVC\Application::getId()

Get application id



* Visibility: **public**




### getPath

    string Chayka\MVC\Application::getPath()

Get application path



* Visibility: **public**




### getRouter

    \Chayka\MVC\Router Chayka\MVC\Application::getRouter()

Get router to setup routing



* Visibility: **public**




### getController

    \Chayka\MVC\Controller Chayka\MVC\Application::getController(string $className, boolean $newController)

Get new or cached controller for classname



* Visibility: **public**


#### Arguments
* $className **string**
* $newController **boolean**



### dispatch

    string Chayka\MVC\Application::dispatch(string|array $request, boolean $newController, \Chayka\MVC\View $forwardedView)

Dispatch requestUri (call the 'controller > action > view' chain)
Cached controller will be used if available.

Specify $newController = true if you need a clean one.

* Visibility: **public**


#### Arguments
* $request **string|array**
* $newController **boolean**
* $forwardedView **[Chayka\MVC\View](Chayka-MVC-View.md)**


