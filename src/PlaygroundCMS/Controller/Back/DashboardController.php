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
    protected $feedService;
    protected $serviceManager;

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
        
        $feeds = $this->getFeedService()->getFeeds();

        return new ViewModel(array("blocks" => $blocks,
                                   "users" => $users,
                                   "pages" => $pages,
                                   "feeds" => $feeds));
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

    /**
     * getUserMapper
     *
     * @return UserMapperInterface
     */
    public function getFeedService()
    {
        if (null === $this->feedService) {
            $this->setFeedService($this->getServiceLocator()->get('playgroundcms_feed_service'));
        }

        return $this->feedService;
    }
    /**
     * setUserMapper
     *
     * @param  UserMapperInterface $userMapper
     * @return User
     */
    public function setFeedService($feedService)
    {
        $this->feedService = $feedService;

        return $this;
    }
}
