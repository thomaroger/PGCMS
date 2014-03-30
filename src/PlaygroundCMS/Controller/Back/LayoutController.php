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
    protected $cmsOptions;

    public function listAction()
    {
        $this->layout()->setVariable('nav', "cms");
        $this->layout()->setVariable('subNav', "page");
        $p = $this->getRequest()->getQuery('page', 1);


        $layouts = $this->getLayoutService()->getLayoutMapper()->findAll();
        
        $nbLayout = count($layouts);

        $layoutsPaginator = new \Zend\Paginator\Paginator(new \Zend\Paginator\Adapter\ArrayAdapter($layouts));
        $layoutsPaginator->setItemCountPerPage(self::MAX_PER_PAGE);
        $layoutsPaginator->setCurrentPageNumber($p);


        return new ViewModel(array('pages'                => $layouts,
                                   'layoutsPaginator'     => $layoutsPaginator,
                                   'nbLayout'             => $nbLayout));
    }

    public function createAction()
    {
        $folderTheme = $this->getCMSOptions()->getThemeFolder();
        var_dump($folderTheme);
        die;
    }

    public function editAction()
    {

    }

    public function removeAction()
    {

    }

    protected function getLayoutService()
    {
        if (!$this->layoutService) {
            $this->layoutService = $this->getServiceLocator()->get('playgroundcms_layout_service');
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