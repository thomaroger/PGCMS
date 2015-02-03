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
use PlaygroundCore\Filter\Slugify;


class BlockController extends AbstractActionController
{
    /**
    * @var MAX_PER_PAGE  Nombre d'item par page
    */
    const MAX_PER_PAGE = 20;

    /**
    * @var Block $blockService Service de block
    */
    protected $blockService;
    /**
    * @var BlockLayoutZone blockLayoutZoneService  Service de BlockLayoutZone
    */
    protected $blockLayoutZoneService;
    /**
    * @var RevisionService revisionService  Service de Revision
    */
    protected $revisionService;
    
    /**
    * listAction : Liste des blocs
    *
    * @return ViewModel $viewModel 
    */
    public function listAction()
    {
        $this->layout()->setVariable('nav', "cms");
        $this->layout()->setVariable('subNav', "block");
        $p = $this->getRequest()->getQuery('page', 1);
        $nbUseBlocks = array();

        $blocks = $this->getBlockService()->getBlockMapper()->findAll();
        
        $nbBlock = count($blocks);

        $blocksPaginator = new \Zend\Paginator\Paginator(new \Zend\Paginator\Adapter\ArrayAdapter($blocks));
        $blocksPaginator->setItemCountPerPage(self::MAX_PER_PAGE);
        $blocksPaginator->setCurrentPageNumber($p);

        foreach ($blocksPaginator as $block) {
            $nbUseBlocks[$block->getId()] = count($this->getBlockLayoutZoneService()->getBlockLayoutZoneMapper()->findBy(array('block' => $block)));
        }


        $blockTypes = $this->getBlockService()->getBlocksType();

        return new ViewModel(array('blocks'                => $blocks,
                                   'blocksPaginator'       => $blocksPaginator,
                                   'blockTypes'            => $blockTypes,
                                   'nbUseBlocks'           => $nbUseBlocks,
                                   'nbBlock'               => $nbBlock));
    }

    /**
    * createAction : Creation d'un bloc en fonction de son type
    * @param string $type : Type de bloc à créer 
    *
    * @return ViewModel $viewModel 
    */
    public function createAction()
    {
        $this->layout()->setVariable('nav', "cms");
        $this->layout()->setVariable('subNav', "block");

        $typeBlock = $this->getEvent()->getRouteMatch()->getParam('type');
        $type = strtolower(str_replace('controller', '-form', $typeBlock));
        $form = $this->getServiceLocator()->get($type);

        $blockTypes = $this->getBlockService()->getBlocksType();

        $form->get('type')->setValue($blockTypes[$typeBlock]);
        $form->get('is_exportable')->setValue(array(0));
        $form->get('is_gallery')->setValue(array(0));

        $return  = array();
        $data = array();
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = array_merge(
                    $request->getPost()->toArray(),
                    $request->getFiles()->toArray()
            );


            $return = $this->getBlockService()->checkBlock($data);

            $data = $return["data"];
            unset($return["data"]);

            if ($return['status'] == 0) {
                $this->getBlockService()->create($data, $form);

                return $this->redirect()->toRoute('admin/playgroundcmsadmin/block');
            }
        }
        $form->setData($data);

        return new ViewModel(array('form'   => $form,
                                   'data'   => $data,
                                   'return' => $return));
    }

    /**
    * createWithoutLayoutAction : Creation d'un bloc en fonction de son type sans layout
    * @param string $type : Type de bloc à créer 
    *
    * @return ViewModel $viewModel 
    */
    public function createWithoutLayoutAction()
    {
        $typeBlock = $this->getEvent()->getRouteMatch()->getParam('type');
        $type = strtolower(str_replace('controller', '-form', $typeBlock));
        $form = $this->getServiceLocator()->get($type);

        $blockTypes = $this->getBlockService()->getBlocksType();

        $form->get('type')->setValue($blockTypes[$typeBlock]);

        $form->get('is_exportable')->setValue(array(0));
        $form->get('is_gallery')->setValue(array(0));

        $viewModel = new ViewModel();
        $viewModel->setTerminal(true);
        $viewModel->setVariables(array('form' => $form));

        return $viewModel;

    }

    /**
    * editAction : Edition d'un bloc
    * @param int $id : Id du bloc à editer
    * @param int $layoutId : Id du layout relié au bloc
    *
    * @return ViewModel $viewModel 
    */
    public function editAction()
    {
        $this->layout()->setVariable('nav', "cms");
        $this->layout()->setVariable('subNav', "block");
        
        $blockId = $this->getEvent()->getRouteMatch()->getParam('id');
        $layoutId = $this->getEvent()->getRouteMatch()->getParam('layoutId', 0);
        $revisionId = $this->getEvent()->getRouteMatch()->getParam('revisionId', 0);
        $block = $this->getBlockService()->getBlockMapper()->findById($blockId);

        $filters = array('type' => get_class($block), 'objectId' => $block->getId());
        $revisions = $this->getRevisionService()->getRevisionMapper()->findByAndOrderBy($filters, array('id' => 'DESC'));

        if(!empty($revisionId)){
            $revision = $this->getRevisionService()->getRevisionMapper()->findById($revisionId);
            $block = unserialize($revision->getObject());
        }

        $slugify = new Slugify;
        $type = strtolower(str_replace('controller', '-form', $slugify->filter($block->getType())));
        $form = $this->getServiceLocator()->get($type);

        $form->bind($block);
        $form->setData($block);
        
        $return  = array();
        $data = array();
        $request = $this->getRequest();
        if ($request->isPost()) {
            
            if(!empty($revisionId)){
                $block = $this->getBlockService()->getBlockMapper()->findById($blockId);
            }

            $data = array_merge(
                    $request->getPost()->toArray(),
                    $request->getFiles()->toArray()
            );


            $return = $this->getBlockService()->checkBlock($data);

            $data = $return["data"];
            unset($return["data"]);

            if ($return['status'] == 0) {
                $this->getBlockService()->update($block, $data, $form);

                if($layoutId > 0) {

                    return $this->redirect()->toRoute('admin/playgroundcmsadmin/blocklayoutzone_edit', array('id' => $layoutId));
                }

                return $this->redirect()->toRoute('admin/playgroundcmsadmin/block');
            }
        }

        if ($block->getIsExportable()) {
            $form->get('export')->setValue("/fr-fr/export-block/".$block->getSlug().'.html');  
        }

        $form->setData($data);

        return new ViewModel(array('form'   => $form,
                                   'block'  => $block,
                                   'revisions'  => $revisions,
                                   'data'   => $data,
                                   'return' => $return));
    }

    /**
    * removeAction : Suppression d'un bloc
    * @param int $id : Id du bloc à editer
    *
    * @return ViewModel $viewModel 
    */
    public function removeAction()
    {
        $blockId = $this->getEvent()->getRouteMatch()->getParam('id');
        $block = $this->getBlockService()->getBlockMapper()->findById($blockId);

        if(empty($block)){

            return $this->redirect()->toRoute('admin/playgroundcmsadmin/block');
        }

        // Suppresion de la page
        $this->getBlockService()->getBlockMapper()->remove($block);
        return $this->redirect()->toRoute('admin/playgroundcmsadmin/block');
    }

    /**
    * getBlockService : Recuperation du service de block
    *
    * @return Block $blockService : blockService 
    */    
    protected function getBlockService()
    {
        if (!$this->blockService) {
            $this->blockService = $this->getServiceLocator()->get('playgroundcms_block_service');
        }

        return $this->blockService;
    }

    /**
    * getBlockLayoutZoneService : Recuperation du service de BlockLayoutZon
    *
    * @return BlockLayoutZone $blockLayoutZoneService : blockLayoutZoneService 
    */
    protected function getBlockLayoutZoneService()
    {
        if (!$this->blockLayoutZoneService) {
            $this->blockLayoutZoneService = $this->getServiceLocator()->get('playgroundcms_blocklayoutzone_service');
        }

        return $this->blockLayoutZoneService;
    }

    /**
     * getRevisionService : Recuperation du service de revision
     *
     * @return RevisionService $revisionService : revisionService
     */
    private function getRevisionService()
    {
        if (null === $this->revisionService) {
            $this->setRevisionService($this->getServiceLocator()->get('playgroundcms_revision_service'));
        }

        return $this->revisionService;
    }

    /**
     * setRevisionService : Setter du service de revision
     * @param  RevisionService $revisionService
     *
     * @return BlockController $this
     */
    private function setRevisionService($revisionService)
    {
        $this->revisionService = $revisionService;

        return $this;
    }
}


