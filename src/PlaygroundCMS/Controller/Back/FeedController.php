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

class FeedController extends AbstractActionController
{
    protected $feedService;
    /**
    * indexAction : Action index du controller de dashboard
    *
    * @return ViewModel $viewModel 
    */
    public function listAction()
    {

        $this->layout()->setVariable('nav', "feeds");
        
        $feeds = $this->getFeedService()->getFeeds();

        return new ViewModel(array("feeds" => $feeds));
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
