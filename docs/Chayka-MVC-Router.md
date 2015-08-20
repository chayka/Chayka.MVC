Chayka\MVC\Router
===============

Instance of Router class dispatches HTTP request to the SomeController-&gt;someAction()
according to the provided rules.




* Class name: Router
* Namespace: Chayka\MVC





Properties
----------


### $routes

    protected array $routes = array()

A set of routes (routing rules)



* Visibility: **protected**


Methods
-------


### addRoute

    \Chayka\MVC\Router Chayka\MVC\Router::addRoute(string $label, string $urlPattern, array $defaults, array $paramPatterns)

Add routes that can be served using our application.

Here are some samples
:controller/?action/*
my_controller/some_action/:some_part/*
my/act/?some/*

prefix ':' - means obligatory param
prefix '?' - means optional param
trailing '*' - means that all the rest params (/param1/value1/param2/value2) should be captured

* Visibility: **public**


#### Arguments
* $label **string**
* $urlPattern **string**
* $defaults **array**
* $paramPatterns **array**



### addRestRoute

    mixed Chayka\MVC\Router::addRestRoute(string $modelSlug, string $restUrlPattern, array $restParamPatterns, string $modelClassName, string $controller, array $defaults)

Add route to rest controller



* Visibility: **public**


#### Arguments
* $modelSlug **string** - &lt;p&gt;e.g. &#039;post-model&#039;&lt;/p&gt;
* $restUrlPattern **string** - &lt;p&gt;e.g &#039;/?id&#039;&lt;/p&gt;
* $restParamPatterns **array** - &lt;p&gt;e.g. [&#039;id&#039;=&gt;&#039;/^\d+$/&#039;]&lt;/p&gt;
* $modelClassName **string** - &lt;p&gt;e.g. &#039;\Chayka\WP\Models\PostModel&#039;&lt;/p&gt;
* $controller **string** - &lt;p&gt;e.g. &#039;post-model&#039;&lt;/p&gt;
* $defaults **array**



### addRestRoutes

    mixed Chayka\MVC\Router::addRestRoutes(string $modelSlug, string $modelsSlug, string $restUrlPattern, array $restParamPatterns, string $modelClassName, string $controller, string $listAction, array $defaults)

Add set of routes to rest controller



* Visibility: **public**


#### Arguments
* $modelSlug **string** - &lt;p&gt;e.g. &#039;post-model&#039;&lt;/p&gt;
* $modelsSlug **string** - &lt;p&gt;e.g. &#039;post-models&#039;&lt;/p&gt;
* $restUrlPattern **string** - &lt;p&gt;e.g &#039;/?id&#039;&lt;/p&gt;
* $restParamPatterns **array** - &lt;p&gt;e.g. [&#039;id&#039;=&gt;&#039;/^\d+$/&#039;]&lt;/p&gt;
* $modelClassName **string** - &lt;p&gt;e.g. &#039;\Chayka\WP\Models\PostModel&#039;&lt;/p&gt;
* $controller **string** - &lt;p&gt;e.g. &#039;post-model&#039;&lt;/p&gt;
* $listAction **string**
* $defaults **array**



### parseRequest

    array Chayka\MVC\Router::parseRequest($requestUri)

Parse request URI and return input assoc array



* Visibility: **public**


#### Arguments
* $requestUri **mixed**



### matchRoute

    string Chayka\MVC\Router::matchRoute($requestUri)

Find the most matching route and return it's label



* Visibility: **public**


#### Arguments
* $requestUri **mixed**



### compareRoute

    integer Chayka\MVC\Router::compareRoute($requestUri, $route)

Compare request URI against provided route object and return it's strength



* Visibility: **public**


#### Arguments
* $requestUri **mixed**
* $route **mixed**



### parseRoute

    array Chayka\MVC\Router::parseRoute(string $requestUri, array $route)

Parse request URI against provided route object



* Visibility: **public**


#### Arguments
* $requestUri **string**
* $route **array**



### assembleRoute

    string Chayka\MVC\Router::assembleRoute(array $params, string $label)

Assembles href from the provided params using the route designated by $label



* Visibility: **public**


#### Arguments
* $params **array**
* $label **string**


