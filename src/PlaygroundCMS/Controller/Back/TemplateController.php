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
    protected $templateService;
    protected $blockService;
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

        if ($request->isPost()) {
            $data = array_merge(
                    $request->getPost()->toArray(),
                    $request->getFiles()->toArray()
            );

            $return = $this->getTemplateService()->checkTemplate($data);
            $data = $return["data"];
            unset($return["data"]);

            if ($return['status'] == 0) {
                $this->getTemplateService()->create($data);

                return $this->redirect()->toRoute('admin/playgroundcmsadmin/template');
            }
        }

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

        if ($request->isPost()) {
            $data = array_merge(
                    $request->getPost()->toArray(),
                    $request->getFiles()->toArray()
            );

            $return = $this->getTemplateService()->checkTemplate($data);
            $data = $return["data"];
            unset($return["data"]);

            if ($return['status'] == 0) {
                $this->getTemplateService()->edit($data);

                return $this->redirect()->toRoute('admin/playgroundcmsadmin/template');
            }
        }

        $blockstype = $this->getBlocksType();

        $files = $this->getPhtmlFiles($folderTheme, $files);
        $files = $this->cleanFiles($this->getCMSOptions()->getThemeFolder(), $files);

        return new ViewModel(array('template' => $template,
                                   'blockstype' => $blockstype,
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

        $blocks = $this->getBlockService()->getBlockMapper()->findAll();
        foreach ($blocks as $block) {
            $templateContext = json_decode($block->getTemplateContext(), true);
            foreach ($templateContext as $key => $value) {
                if ($value  == $template->getFile()) {
                    return $this->redirect()->toRoute('admin/playgroundcmsadmin/template');
                }
            }
        }

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

    public function cleanFiles($path, $files)
    {
        foreach ($files as $key => $file) {
            $files[$key] = str_replace($path, '', $files[$key]);
        }

        return $files;
    }

    public function getBlocksType()
    {
        return $this->getBlockService()->getBlocksType();
    }

    protected function getTemplateService()
    {
        if (!$this->templateService) {
            $this->templateService = $this->getServiceLocator()->get('playgroundcms_template_service');
        }

        return $this->templateService;
    }

    protected function getBlockService()
    {
        if (!$this->blockService) {
            $this->blockService = $this->getServiceLocator()->get('playgroundcms_block_service');
        }

        return $this->blockService;
    }

    protected function getCMSOptions()
    {
        if (!$this->cmsOptions) {
            $this->cmsOptions = $this->getServiceLocator()->get('playgroundcms_module_options');
        }

        return $this->cmsOptions;
    }
}