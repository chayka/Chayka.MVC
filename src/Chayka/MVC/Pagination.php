<?php
/**
 * Chayka.Framework is a framework that enables WordPress development in a MVC/OOP way.
 *
 * More info: https://github.com/chayka/Chayka.Framework
 */

namespace Chayka\MVC;

/**
 * Instance of Pagination class is a pagination model.
 * Since in most cases there is only one pagination model per request,
 * for simplicity it can be accessed as singleton.
 *
 * @package Chayka\MVC
 */
class Pagination{

    /**
     * Total number of pages
     *
     * @var int
     */
    protected $totalPages;

    /**
     * Current page
     *
     * @var int
     */
    protected $currentPage;

    /**
     * Number page links in pack between << 1 ... [pack] ... 100 >>
     *
     * @var int
     */
    protected $packSize = 10;

    /**
     * Pack start index
     *
     * @var int
     */
    protected $packStart = 0;

    /**
     * Pack ending index
     *
     * @var int
     */
    protected $packFinish = 0;

    /**
     * Page nav link pattern.
     * Page number is patterned by '.page.'
     *
     * @var string
     */
    protected $pageLinkPattern = '/page/.page.';

    /**
     * Pagination view template file
     *
     * @var string
     */
    protected $viewTemplate = 'pagination.phtml';

    /**
     * Order of items in navigation, template dependant
     *
     * @var string|array
     */
    protected $itemsOrder = 'previous first rewind pages forward last next';

    /**
     * Singleton instance.
     *
     * @var Pagination
     */
    protected static $instance = null;
    
    /**
     * Get singleton instance
     *
     * @return Pagination
     */
    public static function getInstance(){
        if(!static::$instance){
            static::$instance = new self();
        }
        return static::$instance;
    }

    /**
     * Get total number of pages
     *
     * @return int
     */
    public function getTotalPages() {
        return $this->totalPages;
    }

    /**
     * Set total number of pages
     *
     * @param $totalPages
     * @return $this
     */
    public function setTotalPages($totalPages) {
        $this->packStart = 0;
        $this->packFinish = 0;

        $this->totalPages = $totalPages;
        return $this;
    }

    /**
     * Set total number of list items that should be split into pages
     *
     * @param $totalItems
     * @param int $itemPerPage
     * @return $this
     */
    public function setTotalItems($totalItems, $itemPerPage = 10){
        $totalPages = ceil($totalItems / $itemPerPage);
        $this->setTotalPages($totalPages);
        return $this;
    }

    /**
     * Get current page
     *
     * @return int
     */
    public function getCurrentPage() {
        return $this->currentPage;
    }

    /**
     * Set current page
     *
     * @param $currentPage
     * @return $this
     */
    public function setCurrentPage($currentPage) {
        $this->packStart = 0;
        $this->packFinish = 0;

        $this->currentPage = $currentPage;
        return $this;
    }

    /**
     * Get pack size
     *
     * @return int
     */
    public function getPackSize() {
        return $this->packSize;
    }

    /**
     * Set pack size
     *
     * @param $packSize
     * @return $this
     */
    public function setPackSize($packSize) {
        $this->packStart = 0;
        $this->packFinish = 0;

        $this->packSize = $packSize;
        return $this;
    }

    /**
     * Get page navigation link pattern.
     * Page number is patterned by '.page.'
     *
     * @return string
     */
    public function getPageLinkPattern() {
        return $this->pageLinkPattern;
    }

    /**
     * Set page navigation link pattern.
     * Page number is patterned by '.page.'
     *
     * @param $pageLinkPattern
     * @return $this
     */
    public function setPageLinkPattern($pageLinkPattern) {
        $this->pageLinkPattern = $pageLinkPattern;
        return $this;
    }

    /**
     * Get view template filename.
     * Default is 'pagination.phtml'
     *
     * @return string
     */
    public function getViewTemplate() {
        return $this->viewTemplate;
    }

    /**
     * Setup navigation items order (template dependant)
     *
     * @return array|string
     */
    public function getItemsOrder() {
        return $this->itemsOrder;
    }

    /**
     * Setup navigation items order (template dependant)

     * @param array|string $itemsOrder
     */
    public function setItemsOrder($itemsOrder) {
        $this->itemsOrder = $itemsOrder;
    }

    /**
     * Quick navigation setup
     *
     * @param $linkPattern
     * @param $currentPage
     * @param $totalPages
     * @param int $packSize
     * @return $this
     */
    public function setupPages($linkPattern, $currentPage, $totalPages, $packSize = 10){
        return $this
            ->setPageLinkPattern($linkPattern)
            ->setTotalPages($totalPages)
            ->setCurrentPage($currentPage)
            ->setPackSize($packSize);
    }

    /**
     * Quick navigation setup
     *
     * @param $linkPattern
     * @param $currentPage
     * @param $totalItems
     * @param $itemsPerPage
     * @param int $packSize
     * @return $this
     */
    public function setupItems($linkPattern, $currentPage, $totalItems, $itemsPerPage, $packSize = 10){
        return $this
            ->setPageLinkPattern($linkPattern)
            ->setTotalItems($totalItems, $itemsPerPage)
            ->setCurrentPage($currentPage)
            ->setPackSize($packSize);
    }

    /**
     * Get view template filename.
     * Default is 'pagination.phtml'
     *
     * @param string $viewTemplate
     * @return $this
     */
    public function setViewTemplate($viewTemplate) {
        $this->viewTemplate = $viewTemplate;
        return $this;
    }

    /**
     * Computes pack starting and ending indices
     */
    protected function computePack(){
        if(!$this->packStart){
            $packStart = 1;
            $packFinish = $this->getTotalPages();

            if($this->getPackSize() < $this->getTotalPages()){
                $packStart = $this->getCurrentPage() - floor(($this->getPackSize() -1)/ 2);
                $packFinish = $this->getCurrentPage() + ceil(($this->getPackSize() -1)/ 2);
                $offset = 0;
                if($packStart<1){
                    $offset = 1 - $packStart;
                }
                if($packFinish>$this->getTotalPages()){
                    $offset = $this->getTotalPages() - $packFinish;
                }
                $packStart+=$offset;
                $packFinish+=$offset;
            }
            $this->packStart = $packStart;
            $this->packFinish = $packFinish;
        }
    }

    /**
     * Get first pack page
     *
     * @return int
     */
    public function getPackFirstPage(){
        $this->computePack();
        return $this->packStart;
    }

    /**
     * Get last pack page
     *
     * @return int
     */
    public function getPackLastPage(){
        $this->computePack();
        return $this->packFinish;
    }

    /**
     * Check if page exists
     *
     * @param $page
     * @return bool
     */
    public function pageExists($page){
        return $page > 0 && $page <= $this->getTotalPages();
    }

    /**
     * Get link for the specified page
     *
     * @param $page
     * @return string|null
     */
    public function getPageLink($page){
        return $this->pageExists($page)?
                str_replace('.page.', $page, $this->getPageLinkPattern()):null;
    }

    /**
     * Get link to the previous page
     *
     * @return null|string
     */
    public function getPreviousPageLink(){
        $page = $this->getCurrentPage() - 1;
        return $this->getPageLink($page);
    }

    /**
     * Get link to the next page
     *
     * @return null|string
     */
    public function getNextPageLink(){
        $page = $this->getCurrentPage() + 1;
        return $this->getPageLink($page);
    }

    /**
     * Get link to the previous pack
     *
     * @return null|string
     */
    public function getPreviousPackLink(){
        $page = $this->getPackFirstPage() - 1;
        return $this->getPageLink($page);
    }

    /**
     * Get link to the next pack
     *
     * @return null|string
     */
    public function getNextPackLink(){
        $page = $this->getPackLastPage() + 1;
        return $this->getPageLink($page);
    }

    /**
     * Render pagination
     *
     * @param array $attrs
     * @param string $cssClass
     * @param null|string $jsInstance
     * @return null|string
     */
    public function render($attrs = array(), $cssClass = '', $jsInstance = null ){
        $html = new View();

        $html->assign('model', $this);
        $html->assign('js', $jsInstance);
        $html->assign('attributes', $attrs);
        $html->assign('cssClass', $cssClass);

        return $html->render($this->getViewTemplate());
    }

	/**
     * Render javascript powered pagination
     *
	 * @param $jsInstance
	 * @param string $onClick
	 * @param string $cssClass
	 * @param array $attrs
	 *
	 * @return null|string
	 */
	public function renderJs($jsInstance, $onClick = '', $cssClass = '', $attrs = array()){
		if($onClick){
			$attrs['data-click'] = $onClick;
		}
		return $this->render($attrs, $cssClass, $jsInstance);
	}
}