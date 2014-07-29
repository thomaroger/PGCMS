<?php
/**
* @package : PlaygroundCMS
* @author : troger
* @since : 29/07/2014
*
* Classe de controleur de back des menus
**/

namespace PlaygroundCMS\Controller\Back;

use Zend\View\Model\ViewModel;
use Zend\Mvc\Controller\AbstractActionController;

class MenuController extends AbstractActionController
{

    /**
    * @var $feedService : Service de feed
    */
    protected $menuService;
    
    /**
    * indexAction : Liste des feeds
    *
    * @return ViewModel $viewModel 
    */
    public function listAction()
    {
        $this->layout()->setVariable('nav', "menu");
        $menus = array();

        return new ViewModel(array("menus" => $menus));
    }  

     /**
     * getFeedService : Recuperation du service de feed
     *
     * @return Feed $feedService : feedService
     */
    private function getMenuService()
    {
        if (null === $this->menuService) {
            $this->setMenuService($this->getServiceLocator()->get('playgroundcms_menu_service'));
        }

        return $this->menuService;
    }
    /**
     * setFeedService : Setter du service de feed
     * @param  Feed $feedService
     *
     * @return FeedController $this
     */
    private function setMenuService($menuService)
    {
        $this->menuService = $menuService;

        return $this;
    }
}
