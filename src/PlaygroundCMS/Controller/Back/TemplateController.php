<?php
/**
* @package : PlaygroundCMS
* @author : troger
* @since : 11/04/2014
*
* Classe de controleur de back pour la gestion des templates
**/

namespace PlaygroundCMS\Controller\Back;

use Zend\View\Model\ViewModel;
use Zend\Mvc\Controller\AbstractActionController;

use PlaygroundCMS\Entity\Template;

class TemplateController extends AbstractActionController
{
    /**
    * @var MAX_PER_PAGE  Nombre d'item par page
    */
    const MAX_PER_PAGE = 20;

    /**
    * @var Template $templateService  Service de template
    */
    protected $templateService;
    
    /**
    * @var Block $blockService  Service de block
    */
    protected $blockService;
    
    /**
    * @var ModuleOptions $cmsOptions Option de playgroundcms
    */
    protected $cmsOptions;

    /**
    * indexAction : Liste des templates
    *
    * @return ViewModel $viewModel 
    */
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

        $blockTypes = $this->getBlockService()->getBlocksType();

        $files = $this->getTemplateService()->getTemplates();

        return new ViewModel(array('templates'               => $templates,
                                   'blockTypes'              => $blockTypes,
                                   'files'                   => $files,
                                   'templatesPaginator'      => $templatesPaginator,
                                   'nbTemplates'             => $nbTemplates));
    }

    /**
    * createAction : Creation d'un template
    *
    * @return ViewModel $viewModel 
    */
    public function createAction()
    {
        $return  = array();
        $data = array();
        
        $this->layout()->setVariable('nav', "cms");
        $this->layout()->setVariable('subNav', "template");

        $request = $this->getRequest();

        if ($request->isPost()) {
            $data = array_merge_recursive(
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

        $files = $this->getTemplateService()->getTemplates();

        $blockstype = $this->getBlocksType();

        return new ViewModel(array('files'  => $files,
                                   'blockstype' => $blockstype,
                                   'data'   => $data,
                                   'return' => $return));
    }

    /**
    * editAction : Edition d'une template en fonction d'un id
    * @param int $id : Id du template
    *
    * @return ViewModel $viewModel 
    */
    public function editAction()
    {
        $return  = array();
        $data = array();

        $this->layout()->setVariable('nav', "cms");
        $this->layout()->setVariable('subNav', "template");
        
        $request = $this->getRequest();

        $templateId = $this->getEvent()->getRouteMatch()->getParam('id');
        $template = $this->getTemplateService()->getTemplateMapper()->findById($templateId);

        if(empty($template)){

            return $this->redirect()->toRoute('admin/playgroundcmsadmin/template');
        }

        if ($request->isPost()) {
            $data = array_merge_recursive(
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

        $files = $this->getTemplateService()->getTemplates();

        return new ViewModel(array('template' => $template,
                                   'blockstype' => $blockstype,
                                   'files'  => $files,
                                   'return' => $return));
    }

    /**
    * removeAction : Suppression du template en fonction d'un id
    * @param int $id : Id du template
    *
    * @return ViewModel $viewModel 
    */
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

    /**
    * getBlocksType : Recuperation de l'ensemble des types de blocs
    *
    * @return array $blocksTypes 
    */
    private function getBlocksType()
    {
        return $this->getBlockService()->getBlocksType();
    }

    /**
    * getTemplateService : Recuperation du service de Template
    *
    * @return Template $templateService 
    */
    private function getTemplateService()
    {
        if (!$this->templateService) {
            $this->templateService = $this->getServiceLocator()->get('playgroundcms_template_service');
        }

        return $this->templateService;
    }

    /**
    * getBlockService : Recuperation du service de block
    *
    * @return Block $blockService : blockService 
    */ 
    private function getBlockService()
    {
        if (!$this->blockService) {
            $this->blockService = $this->getServiceLocator()->get('playgroundcms_block_service');
        }

        return $this->blockService;
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