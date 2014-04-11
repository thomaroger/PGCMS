<?php
/**
* @package : PlaygroundCMS
* @author : troger
* @since : 11/04/2014
*
* Classe de controleur de back pour la gestion des template
**/

namespace PlaygroundCMS\Controller\Back;

use Zend\View\Model\ViewModel;
use Zend\Mvc\Controller\AbstractActionController;

use PlaygroundCMS\Entity\Template;

class TemplateController extends AbstractActionController
{
    const MAX_PER_PAGE = 20;
    protected $layoutService;
    protected $cmsOptions;

    public function listAction()
    {
        $this->layout()->setVariable('nav', "cms");
        $this->layout()->setVariable('subNav', "template");
        $p = $this->getRequest()->getQuery('page', 1);


        $templates = $this->getTemplateService()->getTemplateMapper()->findAll();
        
        $nbTemplates = count($templates);

        $templatesPaginator = new \Zend\Paginator\Paginator(new \Zend\Paginator\Adapter\ArrayAdapter($templates));
        $templatesPaginator->setItemCountPerPage(self::MAX_PER_PAGE);
        $templatesPaginator->setCurrentPageNumber($p);


        return new ViewModel(array('templates'               => $templates,
                                   'templatesPaginator'        => $templatesPaginator,
                                   'nbTemplates'             => $nbTemplates));
    }

    public function createAction()
    {
        $return  = array();
        $data = array();
        $files = array();
        $folderTheme = "/".trim($this->getCMSOptions()->getThemeFolder(),'/');
        
        $request = $this->getRequest();

        /*if ($request->isPost()) {
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
        }*/

        $files = $this->getPhtmlFiles($folderTheme, $files);
        $files = $this->cleanFiles($this->getCMSOptions()->getThemeFolder(), $files);

        $blockstype = $this->getBlocksType();

        return new ViewModel(array('files'  => $files,
                                   'blockstype' => $blockstype,
                                   'data'   => $data,
                                   'return' => $return));
    }

    public function editAction()
    {
        $return  = array();
        $data = array();
        $files = array();
        $folderTheme = "/".trim($this->getCMSOptions()->getThemeFolder(),'/');
        
        $request = $this->getRequest();

        $templateId = $this->getEvent()->getRouteMatch()->getParam('id');
        $template = $this->getTemplateService()->getTemplateMapper()->findById($templateId);

        if(empty($template)){

            return $this->redirect()->toRoute('admin/playgroundcmsadmin/template');
        }

       /* if ($request->isPost()) {
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
        }*/


        $files = $this->getPhtmlFiles($folderTheme, $files);
        $files = $this->cleanFiles($this->getCMSOptions()->getThemeFolder(), $files);

        return new ViewModel(array('template' => $template,
                                   'files'  => $files,
                                   'return' => $return));
    }

    public function removeAction()
    {
        $templateId = $this->getEvent()->getRouteMatch()->getParam('id');
        $template = $this->getTemplateService()->getTemplateMapper()->findById($templateId);

        if(empty($template)){

            return $this->redirect()->toRoute('admin/playgroundcmsadmin/template');
        }

        /**
         @todo  Attention à ne pas virer un template qui est utilisé par un bloc
        */


        // Suppresion de la page
        $this->getTemplateService()->getTemplateMapper()->remove($template);

        return $this->redirect()->toRoute('admin/playgroundcmsadmin/template');
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

    public function getBlocksType()
    {
        $blockstype = array();

        $path = __DIR__.'/../../Blocks/';
        $dir = opendir($path);
        while($item = readdir($dir)) {
            if (is_file($sub = $path.'/'.$item)) {
                if(pathinfo($path.'/'.$item, PATHINFO_EXTENSION) == "php") {
                    $blockstype[] = str_replace('Controller\Back', 'Blocks', __NAMESPACE__).'\\'.str_replace('.php','',$item);
                }
            }
        }

        return $blockstype;
    }

    public function cleanFiles($path, $files)
    {
        foreach ($files as $key => $file) {
            $files[$key] = str_replace($path, '', $files[$key]);
        }

        return $files;
    }

    protected function getTemplateService()
    {
        if (!$this->layoutService) {
            $this->layoutService = $this->getServiceLocator()->get('playgroundcms_template_service');
        }

        return $this->layoutService;
    }

    protected function getCMSOptions()
    {
        if (!$this->cmsOptions) {
            $this->cmsOptions = $this->getServiceLocator()->get('playgroundcms_module_options');
        }

        return $this->cmsOptions;
    }
}