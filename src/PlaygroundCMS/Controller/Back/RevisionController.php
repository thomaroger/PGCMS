<?php
/**
* @package : PlaygroundCMS
* @author : troger
* @since : 30/10/2014
*
* Classe de controleur de back des revisions
**/

namespace PlaygroundCMS\Controller\Back;

use Zend\View\Model\ViewModel;
use Zend\Mvc\Controller\AbstractActionController;

class RevisionController extends AbstractActionController
{

    /**
    * @var MAX_PER_PAGE  Nombre d'item par page
    */
    const MAX_PER_PAGE = 20;

    /**
    * @var $feedService : Service de feed
    */
    protected $revisionService;
    
    /**
    * indexAction : Liste des feeds
    *
    * @return ViewModel $viewModel 
    */
    public function listAction()
    {
        $revisionsTab = array();
        $filtersType = array();
        $this->layout()->setVariable('nav', "revisions");
        $p = $this->getRequest()->getQuery('page', 1);

        $revisions = $this->getRevisionService()->getRevisionMapper()->findAll();
        
        $nbRevision = count($revisions);

        foreach ($revisions as $revision) {
            $revisionTab[$revision->getType().'-'.$revision->getObjectId()][] = $revision;
            $filtersType[$revision->getType()] = $revision->getType();
        }

        $revisionsPaginator = new \Zend\Paginator\Paginator(new \Zend\Paginator\Adapter\ArrayAdapter($revisionTab));
        $revisionsPaginator->setItemCountPerPage(self::MAX_PER_PAGE);
        $revisionsPaginator->setCurrentPageNumber($p);

        
        return new ViewModel(array('revisions'              => $revisions,
                                   'revisionsPaginator'     => $revisionsPaginator,
                                   'nbRevision'             => $nbRevision,
                                   'filtersType'            => $filtersType));
           
    }  

    /**
     * getFeedService : Recuperation du service de feed
     *
     * @return Feed $feedService : feedService
     */
    private function getRevisionService()
    {
        if (null === $this->revisionService) {
            $this->setRevisionService($this->getServiceLocator()->get('playgroundcms_revision_service'));
        }

        return $this->revisionService;
    }
    
    /**
     * setFeedService : Setter du service de feed
     * @param  Feed $feedService
     *
     * @return FeedController $this
     */
    private function setRevisionService($revisionService)
    {
        $this->revisionService = $revisionService;

        return $this;
    }
}
