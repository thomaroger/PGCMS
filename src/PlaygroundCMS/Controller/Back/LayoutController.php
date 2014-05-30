<?php
/**
* @package : PlaygroundCMS
* @author : troger
* @since : 30/03/2014
*
* Classe de controleur de back pour la gestion des layouts
**/

namespace PlaygroundCMS\Controller\Back;

use Zend\View\Model\ViewModel;
use Zend\Mvc\Controller\AbstractActionController;

use PlaygroundCMS\Entity\Layout;
use PlaygroundCore\Filter\Slugify;


class LayoutController extends AbstractActionController
{
    /**
    * @var MAX_PER_PAGE  Nombre d'item par page
    */
    const MAX_PER_PAGE = 20;

    /**
    * @var Layout $layoutService Service de Layout
    */
    protected $layoutService;

    /**
    * @var Block $blockService Service de block
    */
    protected $blockService;

    /**
    * @var LayoutZone $layoutZoneService Service de LayoutZone
    */
    protected $layoutZoneService;

    /**
    * @var BlockLayoutZone $blockLayoutZoneService Service de BlockLayoutZone
    */
    protected $blockLayoutZoneService;

    /**
    * @var Options $cmsOptions Option du CMS
    */
    protected $cmsOptions;

    /**
    * listAction : Liste des layouts
    *
    * @return ViewModel $viewModel 
    */
    public function listAction()
    {
        $this->layout()->setVariable('nav', "cms");
        $this->layout()->setVariable('subNav', "layout");
    
        $p = $this->getRequest()->getQuery('page', 1);

        $layouts = $this->getLayoutService()->getLayoutMapper()->findAll();
        
        $nbLayout = count($layouts);

        $layoutsPaginator = new \Zend\Paginator\Paginator(new \Zend\Paginator\Adapter\ArrayAdapter($layouts));
        $layoutsPaginator->setItemCountPerPage(self::MAX_PER_PAGE);
        $layoutsPaginator->setCurrentPageNumber($p);

        $files = $this->getLayoutService()->getLayouts();
        
        return new ViewModel(array('layouts'              => $layouts,
                                   'files'                => $files,
                                   'layoutsPaginator'     => $layoutsPaginator,
                                   'nbLayout'             => $nbLayout));
    }

    /**
    * createAction : Creation de layout
    *
    * @return ViewModel $viewModel 
    */
    public function createAction()
    {
        $this->layout()->setVariable('nav', "cms");
        $this->layout()->setVariable('subNav', "layout");
        $return  = array();
        $data = array();
        $request = $this->getRequest();

        if ($request->isPost()) {
            $data = array_merge(
                    $request->getPost()->toArray(),
                    $request->getFiles()->toArray()
            );

            $return = $this->getLayoutService()->checkLayout($data);
            $data = $return["data"];
            unset($return["data"]);

            if ($return['status'] == 0) {
                $this->getLayoutService()->create($data);

                return $this->redirect()->toRoute('admin/playgroundcmsadmin/layout');
            }
        }

        $files = $this->getLayoutService()->getLayouts();

        return new ViewModel(array('files'  => $files,
                                   'data'   => $data,
                                   'return' => $return));
    }

    /**
    * editAction : Edition d'un layout en fonction de son id
    * @param int $id : id du layout à editer
    *
    * @return ViewModel $viewModel 
    */
    public function editAction()
    {
        $this->layout()->setVariable('nav', "cms");
        $this->layout()->setVariable('subNav', "layout");
        $return  = array();
        $data = array();
        
        $request = $this->getRequest();

        $layoutId = $this->getEvent()->getRouteMatch()->getParam('id');
        $layout = $this->getLayoutService()->getLayoutMapper()->findById($layoutId);

        if(empty($layout)){

            return $this->redirect()->toRoute('admin/playgroundcmsadmin/layout');
        }

        if ($request->isPost()) {
            $data = array_merge(
                    $request->getPost()->toArray(),
                    $request->getFiles()->toArray()
            );

            $return = $this->getLayoutService()->checkLayout($data);
            $data = $return["data"];
            unset($return["data"]);

            if ($return['status'] == 0) {
                $this->getLayoutService()->edit($data);

                return $this->redirect()->toRoute('admin/playgroundcmsadmin/layout');
            }
        }


        $files = $this->getLayoutService()->getLayouts();

        return new ViewModel(array('layout' => $layout,
                                   'files'  => $files,
                                   'return' => $return));
    }

    /**
    * removeAction : Suppression d'un layout en fonction de son id
    * @param int $id : id du layout à supprimer
    *
    * @return ViewModel $viewModel 
    */
    public function removeAction()
    {
        $layoutId = $this->getEvent()->getRouteMatch()->getParam('id');
        $layout = $this->getLayoutService()->getLayoutMapper()->findById($layoutId);

        if(empty($layout)){

            return $this->redirect()->toRoute('admin/playgroundcmsadmin/layout');
        }

        //Suppresion des layoutzone associé 
        $this->getLayoutService()->removeLayoutZone($layout);

        // Suppresion de la page
        $this->getLayoutService()->getLayoutMapper()->remove($layout);

        return $this->redirect()->toRoute('admin/playgroundcmsadmin/layout');
    }

    /**
    * blockLayoutZoneAction : Liste des blocs dans une zone pour un layout donnée
    * @param int $id : id du layout à afficher
    *
    * @return ViewModel $viewModel 
    */
    public function blockLayoutZoneAction()
    {
        $this->layout()->setVariable('nav', "cms");
        $this->layout()->setVariable('subNav', "layout");
        $return  = array();
        $data = array();
        $zones = array();
        
        $request = $this->getRequest();

        $layoutId = $this->getEvent()->getRouteMatch()->getParam('id');
        $layout = $this->getLayoutService()->getLayoutMapper()->findById($layoutId);

        if(empty($layout)){

            return $this->redirect()->toRoute('admin/playgroundcmsadmin/layout');
        }

         if ($request->isPost()) {
            $data = array_merge(
                    $request->getPost()->toArray(),
                    $request->getFiles()->toArray()
            );

            if(!empty($data['layout'])) {
                $return = $this->getBlockLayoutZoneService()->checkData($data);
                $data = $return["data"];
                unset($return["data"]);


                if ($return['status'] == 0) {
                    $this->getBlockLayoutZoneService()->create($data);

                    return $this->redirect()->toRoute('admin/playgroundcmsadmin/blocklayoutzone_edit', array('id' => $layout->getId()));
                }
            } else {
                $slugify = new Slugify;
                $type = strtolower(str_replace('controller', '-form', $slugify->filter($data['type'])));
                $form = $this->getServiceLocator()->get($type);

                $return = $this->getBlockService()->checkBlock($data);

                $data = $return["data"];
                unset($return["data"]);

                if ($return['status'] == 0) {
                    $block = $this->getBlockService()->create($data, $form);
                }

                if (empty($block)) {
                    
                    return $this->redirect()->toRoute('admin/playgroundcmsadmin/blocklayoutzone_edit', array('id' => $layout->getId()));
                }

                $zoneId = $data['layoutZoneId'];
                $data = array();
                $data['layout']['id'] = $layoutId;
                $data['layout']['zone'] = $zoneId;
                $data['layout']['block'] = $block->getId();

                $this->getBlockLayoutZoneService()->create($data);

                return $this->redirect()->toRoute('admin/playgroundcmsadmin/blocklayoutzone_edit', array('id' => $layout->getId()));
            }

        }

        $blockTypes = $this->getBlockService()->getBlocksType();
        $blocksGallery = $this->getBlockService()->getBlockMapper()->findBy(array('isGallery' => 1));

        $zones = $this->getBlocksLayoutZone($layout);

        return new ViewModel(array('layout' => $layout,
                                   'zones'  => $zones,
                                   'blockTypes' => $blockTypes,
                                   'blocksGallery' => $blocksGallery,
                                   'return' => $return));
    }

    /**
    * updateBlockLayoutZoneAction : Modification de la position d'un bloc dans une zone pour un layout donné
    * @param int $id : id du du layout
    * @param int $blocklayoutZoneId : id du blocLayoutZone
    * @param int $position : Position du bloc
    *
    * @return Response $response 
    */
    public function updateBlockLayoutZoneAction()
    {

        $layoutId = $this->getEvent()->getRouteMatch()->getParam('id');
        $blocklayoutZoneId = $this->getEvent()->getRouteMatch()->getParam('blocklayoutZoneId');
        $position = $this->getEvent()->getRouteMatch()->getParam('position');

        $layout = $this->getLayoutService()->getLayoutMapper()->findById($layoutId);

        $blockLayoutZone = $this->getBlockLayoutZoneService()->getBlockLayoutZoneMapper()->findById($blocklayoutZoneId);

        $blockLayoutZone->setPosition($position);

        $this->getBlockLayoutZoneService()->getBlockLayoutZoneMapper()->update($blockLayoutZone);

        // ajout +1 pour les blocks qui seront en dessous de lui
        $blocksLayoutZoneBelow = $this->getBlockLayoutZoneService()->getBlockLayoutZoneMapper()->getBlocksBelow($blockLayoutZone, $position);

        foreach ($blocksLayoutZoneBelow as $blockLayoutZoneBelow) {
            $blockLayoutZoneBelow->setPosition($blockLayoutZoneBelow->getPosition() + 1);
            $this->getBlockLayoutZoneService()->getBlockLayoutZoneMapper()->update($blockLayoutZoneBelow);
        }
        
        $response = $this->getResponse();
        $response->setStatusCode(200);
        $headers = $response->getHeaders();
        $response->setContent(json_encode(array('status' => 0)));

        return $response;
    }

    /**
    * removeBlockLayoutZoneAction : Suppression du bloc dans la zone du layout
    * @param int $id : id du layout 
    * @param int $blocklayoutZoneId : id du bloclayoutzone à supprimer 
    *
    * @return Response $response 
    */
    public function removeBlockLayoutZoneAction()
    {

        $layoutId = $this->getEvent()->getRouteMatch()->getParam('id');
        $blocklayoutZoneId = $this->getEvent()->getRouteMatch()->getParam('blocklayoutZoneId');

        $layout = $this->getLayoutService()->getLayoutMapper()->findById($layoutId);

        $blockLayoutZone = $this->getBlockLayoutZoneService()->getBlockLayoutZoneMapper()->findById($blocklayoutZoneId);

        $this->getBlockLayoutZoneService()->getBlockLayoutZoneMapper()->remove($blockLayoutZone);

        return $this->redirect()->toRoute('admin/playgroundcmsadmin/blocklayoutzone_edit', array('id' => $layout->getId()));
    }

    /**
    * getBlocksLayoutZone : Permet de recuperer les blocs qui sont présent dans les zones du layout
    * @param Layout $layout :  layout 
    *
    * @return Array $zones : Tableau de zone avec les blocs 
    */
    private function getBlocksLayoutZone($layout)
    {
        $zones = array();

        $layoutZones = $this->getLayoutZoneService()->getLayoutZoneMapper()->findBy(array('layout' => $layout->getId()));
        foreach ($layoutZones as $layoutZone) {
            $zone = $layoutZone->getZone();
            $zones[$zone->getName()]['zone'] = $zone;
            $zones[$zone->getName()]['blocks'] = array();

            $blocksLayoutZone = $this->getBlockLayoutZoneService()->getBlockLayoutZoneMapper()->findByAndOrderBy(array('layoutZone' => $layoutZone->getId()), array('position' => 'ASC'));
            foreach ($blocksLayoutZone as $blockLayoutZone) {
                $zones[$zone->getName()]['blocks'][$blockLayoutZone->getId()] = $blockLayoutZone->getBlock();
            }
        }

        return $zones;
    }

    /**
    * getLayoutService : Recuperation du service de Layout
    *
    * @return Layout $layoutService 
    */
    private function getLayoutService()
    {
        if (!$this->layoutService) {
            $this->layoutService = $this->getServiceLocator()->get('playgroundcms_layout_service');
        }

        return $this->layoutService;
    }

    /**
    * getBlockService : Recuperation du service de bloc
    *
    * @return Block $blockService 
    */
    private function getBlockService()
    {
        if (!$this->blockService) {
            $this->blockService = $this->getServiceLocator()->get('playgroundcms_block_service');
        }

        return $this->blockService;
    }

    /**
    * getLayoutZoneService : Recuperation du service de LayoutZone
    *
    * @return LayoutZone $layoutZoneService 
    */
    private function getLayoutZoneService()
    {
        if (!$this->layoutZoneService) {
            $this->layoutZoneService = $this->getServiceLocator()->get('playgroundcms_layoutZone_service');
        }

        return $this->layoutZoneService;
    }

    /**
    * getBlockLayoutZoneService : Recuperation du service de blockLayoutZone
    *
    * @return blockLayoutZone $blockLayoutZoneService 
    */
    private function getBlockLayoutZoneService()
    {
        if (!$this->blockLayoutZoneService) {
            $this->blockLayoutZoneService = $this->getServiceLocator()->get('playgroundcms_blocklayoutZone_service');
        }

        return $this->blockLayoutZoneService;
    }

     /**
    * getCMSOptions : Recuperation des options de playgroundCMS
    *
    * @return ModuleOptions $cmsOptions 
    */
    private function getCMSOptions()
    {
        if (!$this->cmsOptions) {
            $this->cmsOptions = $this->getServiceLocator()->get('playgroundcms_module_options');
        }

        return $this->cmsOptions;
    }
}