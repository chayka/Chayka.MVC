Chayka\MVC\RestController
===============

Class RestController extends Controller and provides routing to it&#039;s Actions
based on the type of HTTP request method.

HTTP POST    -> createAction()   | postAction()
HTTP GET     -> readAction()     | getAction()
HTTP PUT     -> updateAction()   | putAction
HTTP DELETE  -> deleteAction()


* Class name: RestController
* Namespace: Chayka\MVC
* Parent class: [Chayka\MVC\Controller](Chayka-MVC-Controller.md)





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


### init

    mixed Chayka\MVC\Controller::init()

This function is called before action is dispatched.

You can override it to create some custom initialization

* Visibility: **public**
* This method is defined by [Chayka\MVC\Controller](Chayka-MVC-Controller.md)




### listAction

    null Chayka\MVC\RestController::listAction(boolean $respond)

Action when get root requested.

Called to fetch list of entities

* Visibility: **public**


#### Arguments
* $respond **boolean** - &lt;p&gt;if true JSON wrapped entity will be responded&lt;/p&gt;



### createAction

    null Chayka\MVC\RestController::createAction(boolean $respond)

Create entity by issue of POST request



* Visibility: **public**


#### Arguments
* $respond **boolean** - &lt;p&gt;if true JSON wrapped entity will be responded&lt;/p&gt;



### readAction

    null Chayka\MVC\RestController::readAction(boolean $respond)

Read entity by issue GET request



* Visibility: **public**


#### Arguments
* $respond **boolean**



### updateAction

    null Chayka\MVC\RestController::updateAction(boolean $respond)

Update entity by issue of PUT request



* Visibility: **public**


#### Arguments
* $respond **boolean** - &lt;p&gt;if true JSON wrapped entity will be responded&lt;/p&gt;



### deleteAction

    null Chayka\MVC\RestController::deleteAction(boolean $respond)

Delete entity by issue of DELETE request



* Visibility: **public**


#### Arguments
* $respond **boolean** - &lt;p&gt;if true JSON wrapped entity will be responded&lt;/p&gt;



### indexAction

    null Chayka\MVC\RestController::indexAction(boolean $respond)

Alias of listAction - default action,
both can be redefined



* Visibility: **public**


#### Arguments
* $respond **boolean**



### postAction

    null Chayka\MVC\RestController::postAction(boolean $respond)

Alias of createAction, both can be redefined



* Visibility: **public**


#### Arguments
* $respond **boolean**



### getAction

    null Chayka\MVC\RestController::getAction(boolean $respond)

Alias of readAction, both can be redefined



* Visibility: **public**


#### Arguments
* $respond **boolean**



### putAction

    null Chayka\MVC\RestController::putAction(boolean $respond)

Alias of updateAction, both can be redefined



* Visibility: **public**


#### Arguments
* $respond **boolean**



### dispatch

    null|string Chayka\MVC\Controller::dispatch(array $request, \Chayka\MVC\View $forwardedView)

Dispatch request.

Request is formed by Application.
This function finds appropriate action callback and invokes it

* Visibility: **public**
* This method is defined by [Chayka\MVC\Controller](Chayka-MVC-Controller.md)


#### Arguments
* $request **array**
* $forwardedView **[Chayka\MVC\View](Chayka-MVC-View.md)**



### __construct

    mixed Chayka\MVC\Controller::__construct(\Chayka\MVC\Application $application)

Controller constructor



* Visibility: **public**
* This method is defined by [Chayka\MVC\Controller](Chayka-MVC-Controller.md)


#### Arguments
* $application **[Chayka\MVC\Application](Chayka-MVC-Application.md)**



### forward

    boolean Chayka\MVC\Controller::forward($action, string $controller)

After dispatching current action forwards processing to specified action controller.



* Visibility: **public**
* This method is defined by [Chayka\MVC\Controller](Chayka-MVC-Controller.md)


#### Arguments
* $action **mixed**
* $controller **string**



### getApplication

    \Chayka\MVC\Application Chayka\MVC\Controller::getApplication()

Get Application instance



* Visibility: **public**
* This method is defined by [Chayka\MVC\Controller](Chayka-MVC-Controller.md)




### path2action

    string Chayka\MVC\Controller::path2action($path)

Convert action path string to callback name



* Visibility: **public**
* This method is **static**.
* This method is defined by [Chayka\MVC\Controller](Chayka-MVC-Controller.md)


#### Arguments
* $path **mixed**



### path2controller

    string Chayka\MVC\Controller::path2controller($path)

Convert controller path string to controller classname



* Visibility: **public**
* This method is **static**.
* This method is defined by [Chayka\MVC\Controller](Chayka-MVC-Controller.md)


#### Arguments
* $path **mixed**



### action2path

    string Chayka\MVC\Controller::action2path($action)

Convert action callback name to path string



* Visibility: **public**
* This method is **static**.
* This method is defined by [Chayka\MVC\Controller](Chayka-MVC-Controller.md)


#### Arguments
* $action **mixed**



### controller2path

    string Chayka\MVC\Controller::controller2path($controller)

Convert controller classname to path string



* Visibility: **public**
* This method is **static**.
* This method is defined by [Chayka\MVC\Controller](Chayka-MVC-Controller.md)


#### Arguments
* $controller **mixed**



### getPagination

    \Chayka\MVC\Pagination Chayka\MVC\Controller::getPagination($templatePath)

Get Pagination singleton instance



* Visibility: **public**
* This method is defined by [Chayka\MVC\Controller](Chayka-MVC-Controller.md)


#### Arguments
* $templatePath **mixed**


