<?php
/**
* @package : PlaygroundCMS
* @author : troger
* @since : 25/03/2014
*
* Classe de controleur  de back du dashboard du CMS
**/

namespace PlaygroundCMS\Controller\Back;

use Zend\View\Model\ViewModel;
use Zend\Mvc\Controller\AbstractActionController;

class DashboardController extends AbstractActionController
{

    protected $userService;
    protected $blockMapper;
    protected $pageMapper;
    protected $serviceManager;
    protected $layoutMapper;
    protected $zoneMapper;

    /**
    * indexAction : Action index du controller de dashboard
    *
    * @return ViewModel $viewModel 
    */
    public function indexAction()
    {

        $this->layout()->setVariable('nav', "dashboard");
        
        $blocks = $this->getBlockMapper()->findAll();
        $pages = $this->getPageMapper()->findAll();
        $users = $this->getUserService()->findAll();
        $layouts = $this->getLayoutMapper()->findAll();
        $zones = $this->getZoneMapper()->findAll();
        
        $feeds = $this->getFeeds($blocks, $pages, $users, $layouts, $zones);

        return new ViewModel(array("blocks" => $blocks,
                                   "users" => $users,
                                   "pages" => $pages,
                                   "feeds" => $feeds));
    }

    public function getFeeds($blocks, $pages, $users, $layouts, $zones)
    {
        $feeds = array();
        foreach ($blocks as $block) {
            $feeds[$block->getCreatedAt()->getTimestamp().''.$block->getId()] = $block;
        }
        foreach ($users as $user) {
            $feeds[$user->getCreatedAt()->getTimestamp().''.$user->getId()] = $user;
        }
        foreach ($pages as $page) {
            $feeds[$page->getCreatedAt()->getTimestamp().''.$page->getId()] = $page;
        }
        foreach ($layouts as $layout) {
            $feeds[$layout->getCreatedAt()->getTimestamp().''.$layout->getId()] = $layout;
        }
        foreach ($zones as $zone) {
            $feeds[$zone->getCreatedAt()->getTimestamp().''.$zone->getId()] = $zone;
        }

        krsort($feeds);
        
        return $feeds;
    }


    protected function getBlockMapper()
    {
        if (empty($this->blockMapper)) {
            $this->setBlockMapper($this->getServiceLocator()->get('playgroundcms_block_mapper'));
        }
        return $this->blockMapper;
    }

    protected function setBlockMapper($blockMapper)
    {
        $this->blockMapper = $blockMapper;

        return $this;
    }

    protected function getPageMapper()
    {
        if (empty($this->pageMapper)) {
            $this->setPageMapper($this->getServiceLocator()->get('playgroundcms_page_mapper'));
        }

        return $this->pageMapper;
    }

    protected function setPageMapper($pageMapper)
    {
        $this->pageMapper = $pageMapper;

        return $this;
    }

    protected function getLayoutMapper()
    {
        if (empty($this->layoutMapper)) {
            $this->setLayoutMapper($this->getServiceLocator()->get('playgroundcms_layout_mapper'));
        }

        return $this->layoutMapper;
    }

    protected function setLayoutMapper($layoutMapper)
    {
        $this->layoutMapper = $layoutMapper;

        return $this;
    }

    protected function getZoneMapper()
    {
        if (empty($this->zoneMapper)) {
            $this->setZoneMapper($this->getServiceLocator()->get('playgroundcms_zone_mapper'));
        }

        return $this->zoneMapper;
    }

    protected function setZoneMapper($zoneMapper)
    {
        $this->zoneMapper = $zoneMapper;

        return $this;
    }

    /**
     * getUserMapper
     *
     * @return UserMapperInterface
     */
    public function getUserService()
    {
        if (null === $this->userService) {
            $this->setUserService($this->getServiceLocator()->get('playgrounduser_user_service'));
        }

        return $this->userService;
    }
    /**
     * setUserMapper
     *
     * @param  UserMapperInterface $userMapper
     * @return User
     */
    public function setUserService($userService)
    {
        $this->userService = $userService;

        return $this;
    }
}
