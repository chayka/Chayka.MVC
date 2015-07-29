Chayka\MVC\Pagination
===============

Instance of Pagination class is a pagination model.

Since in most cases there is only one pagination model per request,
for simplicity it can be accessed as singleton.


* Class name: Pagination
* Namespace: Chayka\MVC





Properties
----------


### $totalPages

    protected integer $totalPages

Total number of pages



* Visibility: **protected**


### $currentPage

    protected integer $currentPage

Current page



* Visibility: **protected**


### $packSize

    protected integer $packSize = 10

Number page links in pack between << 1 .

.. [pack] ... 100 >>

* Visibility: **protected**


### $packStart

    protected integer $packStart

Pack start index



* Visibility: **protected**


### $packFinish

    protected integer $packFinish

Pack ending index



* Visibility: **protected**


### $pageLinkPattern

    protected string $pageLinkPattern = '/page/.page.'

Page nav link pattern.

Page number is patterned by '.page.'

* Visibility: **protected**


### $viewTemplate

    protected string $viewTemplate = 'pagination.phtml'

Pagination view template file



* Visibility: **protected**


### $itemsOrder

    protected string $itemsOrder = 'previous first rewind pages forward last next'

Order of items in navigation, template dependant



* Visibility: **protected**


### $instance

    protected \Chayka\MVC\Pagination $instance = null

Singleton instance.



* Visibility: **protected**
* This property is **static**.


Methods
-------


### getInstance

    \Chayka\MVC\Pagination Chayka\MVC\Pagination::getInstance()

Get singleton instance



* Visibility: **public**
* This method is **static**.




### getTotalPages

    integer Chayka\MVC\Pagination::getTotalPages()

Get total number of pages



* Visibility: **public**




### setTotalPages

    \Chayka\MVC\Pagination Chayka\MVC\Pagination::setTotalPages($totalPages)

Set total number of pages



* Visibility: **public**


#### Arguments
* $totalPages **mixed**



### setTotalItems

    \Chayka\MVC\Pagination Chayka\MVC\Pagination::setTotalItems($totalItems, integer $itemPerPage)

Set total number of list items that should be split into pages



* Visibility: **public**


#### Arguments
* $totalItems **mixed**
* $itemPerPage **integer**



### getCurrentPage

    integer Chayka\MVC\Pagination::getCurrentPage()

Get current page



* Visibility: **public**




### setCurrentPage

    \Chayka\MVC\Pagination Chayka\MVC\Pagination::setCurrentPage($currentPage)

Set current page



* Visibility: **public**


#### Arguments
* $currentPage **mixed**



### getPackSize

    integer Chayka\MVC\Pagination::getPackSize()

Get pack size



* Visibility: **public**




### setPackSize

    \Chayka\MVC\Pagination Chayka\MVC\Pagination::setPackSize($packSize)

Set pack size



* Visibility: **public**


#### Arguments
* $packSize **mixed**



### getPageLinkPattern

    string Chayka\MVC\Pagination::getPageLinkPattern()

Get page navigation link pattern.

Page number is patterned by '.page.'

* Visibility: **public**




### setPageLinkPattern

    \Chayka\MVC\Pagination Chayka\MVC\Pagination::setPageLinkPattern($pageLinkPattern)

Set page navigation link pattern.

Page number is patterned by '.page.'

* Visibility: **public**


#### Arguments
* $pageLinkPattern **mixed**



### getViewTemplate

    string Chayka\MVC\Pagination::getViewTemplate()

Get view template filename.

Default is 'pagination.phtml'

* Visibility: **public**




### getItemsOrder

    array|string Chayka\MVC\Pagination::getItemsOrder()

Setup navigation items order (template dependant)



* Visibility: **public**




### setItemsOrder

    mixed Chayka\MVC\Pagination::setItemsOrder(array|string $itemsOrder)

Setup navigation items order (template dependant)



* Visibility: **public**


#### Arguments
* $itemsOrder **array|string**



### setupPages

    \Chayka\MVC\Pagination Chayka\MVC\Pagination::setupPages($linkPattern, $currentPage, $totalPages, integer $packSize)

Quick navigation setup



* Visibility: **public**


#### Arguments
* $linkPattern **mixed**
* $currentPage **mixed**
* $totalPages **mixed**
* $packSize **integer**



### setupItems

    \Chayka\MVC\Pagination Chayka\MVC\Pagination::setupItems($linkPattern, $currentPage, $totalItems, $itemsPerPage, integer $packSize)

Quick navigation setup



* Visibility: **public**


#### Arguments
* $linkPattern **mixed**
* $currentPage **mixed**
* $totalItems **mixed**
* $itemsPerPage **mixed**
* $packSize **integer**



### setViewTemplate

    \Chayka\MVC\Pagination Chayka\MVC\Pagination::setViewTemplate(string $viewTemplate)

Get view template filename.

Default is 'pagination.phtml'

* Visibility: **public**


#### Arguments
* $viewTemplate **string**



### computePack

    mixed Chayka\MVC\Pagination::computePack()

Computes pack starting and ending indices



* Visibility: **protected**




### getPackFirstPage

    integer Chayka\MVC\Pagination::getPackFirstPage()

Get first pack page



* Visibility: **public**




### getPackLastPage

    integer Chayka\MVC\Pagination::getPackLastPage()

Get last pack page



* Visibility: **public**




### pageExists

    boolean Chayka\MVC\Pagination::pageExists($page)

Check if page exists



* Visibility: **public**


#### Arguments
* $page **mixed**



### getPageLink

    string|null Chayka\MVC\Pagination::getPageLink($page)

Get link for the specified page



* Visibility: **public**


#### Arguments
* $page **mixed**



### getPreviousPageLink

    null|string Chayka\MVC\Pagination::getPreviousPageLink()

Get link to the previous page



* Visibility: **public**




### getNextPageLink

    null|string Chayka\MVC\Pagination::getNextPageLink()

Get link to the next page



* Visibility: **public**




### getPreviousPackLink

    null|string Chayka\MVC\Pagination::getPreviousPackLink()

Get link to the previous pack



* Visibility: **public**




### getNextPackLink

    null|string Chayka\MVC\Pagination::getNextPackLink()

Get link to the next pack



* Visibility: **public**




### render

    null|string Chayka\MVC\Pagination::render(array $attrs, string $cssClass, null|string $jsInstance)

Render pagination



* Visibility: **public**


#### Arguments
* $attrs **array**
* $cssClass **string**
* $jsInstance **null|string**



### renderJs

    null|string Chayka\MVC\Pagination::renderJs($jsInstance, string $onClick, string $cssClass, array $attrs)

Render javascript powered pagination



* Visibility: **public**


#### Arguments
* $jsInstance **mixed**
* $onClick **string**
* $cssClass **string**
* $attrs **array**


