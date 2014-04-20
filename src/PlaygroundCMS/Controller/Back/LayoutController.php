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

class LayoutController extends AbstractActionController
{
    const MAX_PER_PAGE = 20;
    protected $layoutService;
    protected $layoutZoneService;
    protected $blockLayoutZoneService;
    protected $cmsOptions;

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


        return new ViewModel(array('layouts'                => $layouts,
                                   'layoutsPaginator'     => $layoutsPaginator,
                                   'nbLayout'             => $nbLayout));
    }

    public function createAction()
    {
        $this->layout()->setVariable('nav', "cms");
        $this->layout()->setVariable('subNav', "layout");
        $return  = array();
        $data = array();
        $files = array();
        $folderTheme = "/".trim($this->getCMSOptions()->getThemeFolder(),'/');
        
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

        $files = $this->getPhtmlFiles($folderTheme, $files);
        $files = $this->cleanFiles($this->getCMSOptions()->getThemeFolder(), $files);

        return new ViewModel(array('files'  => $files,
                                   'data'   => $data,
                                   'return' => $return));
    }

    public function editAction()
    {
        $this->layout()->setVariable('nav', "cms");
        $this->layout()->setVariable('subNav', "layout");
        $return  = array();
        $data = array();
        $files = array();
        $folderTheme = "/".trim($this->getCMSOptions()->getThemeFolder(),'/');
        
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


        $files = $this->getPhtmlFiles($folderTheme, $files);
        $files = $this->cleanFiles($this->getCMSOptions()->getThemeFolder(), $files);

        return new ViewModel(array('layout' => $layout,
                                   'files'  => $files,
                                   'return' => $return));
    }

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

    public function blocklayoutzoneAction()
    {
        $this->layout()->setVariable('nav', "cms");
        $this->layout()->setVariable('subNav', "layout");
        $return  = array();
        $data = array();
        $zones = array();
        $folderTheme = "/".trim($this->getCMSOptions()->getThemeFolder(),'/');
        
        $request = $this->getRequest();

        $layoutId = $this->getEvent()->getRouteMatch()->getParam('id');
        $layout = $this->getLayoutService()->getLayoutMapper()->findById($layoutId);

        if(empty($layout)){

            return $this->redirect()->toRoute('admin/playgroundcmsadmin/layout');
        }


        $zones = $this->getBlocksLayoutZone($layout);

        return new ViewModel(array('layout' => $layout,
                                   'zones'  => $zones,
                                   'return' => $return));
    }

    public function getPhtmlFiles($path, $files)
    {
        $dir = opendir($path);
        while($item = readdir($dir)) {
            if (is_file($sub = $path.'/'.$item)) {
                if(pathinfo($path.'/'.$item, PATHINFO_EXTENSION) == "phtml") {
                    $files[] = $sub;
                }
            } else {
                if($item != "." and $item != "..") {
                    $files = $this->getPhtmlFiles($sub,$files); 
                }
            }
        }
        return($files);
    }

    public function cleanFiles($path, $files)
    {
        foreach ($files as $key => $file) {
            $files[$key] = str_replace($path, '', $files[$key]);
        }

        return $files;
    }


    public function getBlocksLayoutZone($layout)
    {
        $zones = array();

        $layoutZones = $this->getLayoutZoneService()->getLayoutZoneMapper()->findBy(array('layout' => $layout->getId()));
        foreach ($layoutZones as $layoutZone) {
            $zone = $layoutZone->getZone();
            $zones[$zone->getName()]['zone'] = $zone;
            $zones[$zone->getName()]['blocks'] = array();

            $blocksLayoutZone = $this->getBlockLayoutZoneService()->getBlockLayoutZoneMapper()->findByAndOrderBy(array('layoutZone' => $layoutZone->getId()), array('position' => 'ASC'));
            foreach ($blocksLayoutZone as $blockLayoutZone) {
                $zones[$zone->getName()]['blocks'][] = $blockLayoutZone->getBlock();
            }
        }

        return $zones;
    }

    protected function getLayoutService()
    {
        if (!$this->layoutService) {
            $this->layoutService = $this->getServiceLocator()->get('playgroundcms_layout_service');
        }

        return $this->layoutService;
    }

    public function getLayoutZoneService()
    {
        if (!$this->layoutZoneService) {
            $this->layoutZoneService = $this->getServiceLocator()->get('playgroundcms_layoutZone_service');
        }

        return $this->layoutZoneService;
    }

    public function getBlockLayoutZoneService()
    {
        if (!$this->blockLayoutZoneService) {
            $this->blockLayoutZoneService = $this->getServiceLocator()->get('playgroundcms_blocklayoutZone_service');
        }

        return $this->blockLayoutZoneService;
    }

    protected function getCMSOptions()
    {
        if (!$this->cmsOptions) {
            $this->cmsOptions = $this->getServiceLocator()->get('playgroundcms_module_options');
        }

        return $this->cmsOptions;
    }
}