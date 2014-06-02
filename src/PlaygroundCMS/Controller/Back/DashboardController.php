<?php
/**
* @package : PlaygroundCMS
* @author : troger
* @since : 25/03/2014
*
* Classe de controleur de back du dashboard du CMS
**/

namespace PlaygroundCMS\Controller\Back;

use Zend\View\Model\ViewModel;
use Zend\Mvc\Controller\AbstractActionController;

class DashboardController extends AbstractActionController
{
    /**
    * @var User $userService : Service User
    */
    protected $userService;
    /**
    * @var Block $blockMapper : Mapper Block
    */
    protected $blockMapper;
    /**
    * @var Page $pageMapper : Mapper Page
    */
    protected $pageMapper;
    /**
    * @var Feed $feedService : Service feed
    */
    protected $feedService;
    /**
    * @var Article $articleMapper : Mapper article
    */
    protected $articleMapper;

    protected $commentMapper;

    /**
    * indexAction : Dashboard
    *
    * @return ViewModel $viewModel 
    */
    public function indexAction()
    {

        $this->layout()->setVariable('nav', "dashboard");
        
        $blocks = $this->getBlockMapper()->findAll();
        $pages = $this->getPageMapper()->findAll();
        $users = $this->getUserService()->findAll();
        $articles = $this->getArticleMapper()->findAll();
        $comments = $this->getCommentMapper()->findAll();
        $polls = $this->getPollMapper()->findAll();
        
        $feeds = $this->getFeedService()->getFeeds();

        return new ViewModel(array("blocks" => $blocks,
                                   "users" => $users,
                                   "pages" => $pages,
                                   "articles" => $articles,
                                   "comments" => $comments,
                                   "polls" => $polls,
                                   "feeds" => $feeds));
    }

    /**
    * getBlockMapper : Recuperation du mapper de block
    *
    * @return Block $blockMapper 
    */
    private function getBlockMapper()
    {
        if (empty($this->blockMapper)) {
            $this->setBlockMapper($this->getServiceLocator()->get('playgroundcms_block_mapper'));
        }
        
        return $this->blockMapper;
    }

    /**
    * setBlockMapper : Setter du mapper de block
    * @param Block $blockMapper : blockMapper
    *
    * @return DashboardController $this
    */
    private function setBlockMapper($blockMapper)
    {
        $this->blockMapper = $blockMapper;

        return $this;
    }


     /**
    * getBlockMapper : Recuperation du mapper de block
    *
    * @return Block $blockMapper 
    */
    private function getPollMapper()
    {
        if (empty($this->pollMapper)) {
            $this->setPollMapper($this->getServiceLocator()->get('playgroundpublishing_poll_mapper'));
        }
        
        return $this->pollMapper;
    }

    /**
    * setBlockMapper : Setter du mapper de block
    * @param Block $blockMapper : blockMapper
    *
    * @return DashboardController $this
    */
    private function setPollMapper($pollMapper)
    {
        $this->pollMapper = $pollMapper;

        return $this;
    }

    /**
    * getPageMapper : Recuperation du mapper de page
    *
    * @return Page $pageMapper 
    */
    private function getPageMapper()
    {
        if (empty($this->pageMapper)) {
            $this->setPageMapper($this->getServiceLocator()->get('playgroundcms_page_mapper'));
        }

        return $this->pageMapper;
    }
    
    /**
    * setPageMapper : Setter du mapper de page
    * @param Page $pageMapper : pageMapper
    *
    * @return DashboardController $this
    */
    private function setPageMapper($pageMapper)
    {
        $this->pageMapper = $pageMapper;

        return $this;
    }

   /**
    * getUserService : Recuperation du service d'utilisateur
    *
    * @return User $userService 
    */
    private function getUserService()
    {
        if (null === $this->userService) {
            $this->setUserService($this->getServiceLocator()->get('playgrounduser_user_service'));
        }

        return $this->userService;
    }
    
     /**
    * setUserService : Setter du service d'utilisateur
    * @param User $userService : userService
    *
    * @return DashboardController $this
    */
    private function setUserService($userService)
    {
        $this->userService = $userService;

        return $this;
    }

    /**
    * getFeedService : Recuperation du service de feed
    *
    * @return Feed $feedService 
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
    * @param Feed $feedService : feedService
    *
    * @return DashboardController $this
    */
    private function setFeedService($feedService)
    {
        $this->feedService = $feedService;

        return $this;
    }

    /**
    * getArticleMapper : Recuperation du mapper de l'article
    *
    * @return Article $articleMapper 
    */
    private function getArticleMapper()
    {
        if (null === $this->articleMapper) {
            $this->setArticleMapper($this->getServiceLocator()->get('playgroundpublishing_article_mapper'));
        }

        return $this->articleMapper;
    }
    
     /**
    * setArticleMapper : Setter du mapper d'utilisateur
    * @param Article $articleMapper : articleMapper
    *
    * @return DashboardController $this
    */
    private function setArticleMapper($articleMapper)
    {
        $this->articleMapper = $articleMapper;

        return $this;
    }

    /**
    * getArticleMapper : Recuperation du mapper de l'article
    *
    * @return Article $articleMapper 
    */
    private function getCommentMapper()
    {
        if (null === $this->commentMapper) {
            $this->setCommentMapper($this->getServiceLocator()->get('playgroundpublishing_comment_mapper'));
        }

        return $this->commentMapper;
    }
    
     /**
    * setArticleMapper : Setter du mapper d'utilisateur
    * @param Article $articleMapper : articleMapper
    *
    * @return DashboardController $this
    */
    private function setCommentMapper($commentMapper)
    {
        $this->commentMapper = $commentMapper;

        return $this;
    }
}
