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
    protected $blockLayoutZoneService;
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
        $nbUseBlock = array();

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

    public function createAction()
    {
        $typeBlock = $this->getEvent()->getRouteMatch()->getParam('type');
        $type = strtolower(str_replace('Controller', '_form', $typeBlock));
        $form = $this->getServiceLocator()->get('playgroundcms_blocks_'.$type);

        $form->get('type')->setValue('PlaygroundCMS\Blocks\\'.$typeBlock);
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

    public function editAction()
    {
        $blockId = $this->getEvent()->getRouteMatch()->getParam('id');
        $block = $this->getBlockService()->getBlockMapper()->findById($blockId);


        $type = strtolower(str_replace(array('PlaygroundCMS\Blocks\\', 'Controller'), array('', '_form'), $block->getType()));
        $form = $this->getServiceLocator()->get('playgroundcms_blocks_'.$type);

        $form->bind($block);
        $form->setData($block);
        
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
                $this->getBlockService()->update($block, $data, $form);

                return $this->redirect()->toRoute('admin/playgroundcmsadmin/block');
            }
        }

        if ($block->getIsExportable()) {
            $form->get('export')->setValue("/fr-fr/export-block/".$block->getSlug().'.html');  
        }

        $form->setData($data);

        return new ViewModel(array('form'   => $form,
                                   'block'  => $block,
                                   'data'   => $data,
                                   'return' => $return));
    }

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


    protected function getBlockService()
    {
        if (!$this->blockService) {
            $this->blockService = $this->getServiceLocator()->get('playgroundcms_block_service');
        }

        return $this->blockService;
    }

    protected function getBlockLayoutZoneService()
    {
        if (!$this->blockLayoutZoneService) {
            $this->blockLayoutZoneService = $this->getServiceLocator()->get('playgroundcms_blocklayoutzone_service');
        }

        return $this->blockLayoutZoneService;
    }
}


