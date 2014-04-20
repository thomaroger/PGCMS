<?php
/**
* @package : PlaygroundCMS
* @author : troger
* @since : 13/03/2014
*
* Classe de controleur de back pour la gestion des blocks
**/

namespace PlaygroundCMS\Controller\Back;

use Zend\View\Model\ViewModel;
use Zend\Mvc\Controller\AbstractActionController;

class BlockController extends AbstractActionController
{
    const MAX_PER_PAGE = 20;

    protected $blockService;
    /**
    * indexAction : Action index du controller de block
    *
    * @return ViewModel $viewModel 
    */
    public function listAction()
    {
        $this->layout()->setVariable('nav', "cms");
        $this->layout()->setVariable('subNav', "block");
        $p = $this->getRequest()->getQuery('page', 1);


        $blocks = $this->getBlockService()->getBlockMapper()->findAll();
        
        $nbBlock = count($blocks);

        $blocksPaginator = new \Zend\Paginator\Paginator(new \Zend\Paginator\Adapter\ArrayAdapter($blocks));
        $blocksPaginator->setItemCountPerPage(self::MAX_PER_PAGE);
        $blocksPaginator->setCurrentPageNumber($p);

        return new ViewModel(array('blocks'                => $blocks,
                                   'blocksPaginator'       => $blocksPaginator,
                                   'nbBlock'               => $nbBlock));
    }


    protected function getBlockService()
    {
        if (!$this->blockService) {
            $this->blockService = $this->getServiceLocator()->get('playgroundcms_block_service');
        }

        return $this->blockService;
    }
}
