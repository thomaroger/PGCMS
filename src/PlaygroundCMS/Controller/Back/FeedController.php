<?php
/**
* @package : PlaygroundCMS
* @author : troger
* @since : 25/03/2014
*
* Classe de controleur de back des feeds
**/

namespace PlaygroundCMS\Controller\Back;

use Zend\View\Model\ViewModel;
use Zend\Mvc\Controller\AbstractActionController;

class FeedController extends AbstractActionController
{
    /**
    * @var $feedService : Service de feed
    */
    protected $feedService;
    
    /**
    * indexAction : Liste des feeds
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
     * getFeedService : Recuperation du service de feed
     *
     * @return Feed $feedService : feedService
     */
    private function getFeedService()
    {
        if (null === $this->feedService) {
            $this->setFeedService($this->getServiceLocator()->get('playgroundcms_feed_service'));
        }

        return $this->feedService;
    }
    /**
     * setFeedService : Setter du service de feed
     * @param  Feed $feedService
     *
     * @return FeedController $this
     */
    private function setFeedService($feedService)
    {
        $this->feedService = $feedService;

        return $this;
    }
}
