<?php
/**
* @package : PlaygroundCMS
* @author : troger
* @since : 25/03/2014
*
* Classe de controleur de back pour la gestion des pages
**/

namespace PlaygroundCMS\Controller\Back;

use Zend\View\Model\ViewModel;
use Zend\Mvc\Controller\AbstractActionController;
use PlaygroundCMS\Security\Credential;
use PlaygroundCMS\Entity\Page;

class PageController extends AbstractActionController
{
    const MAX_PER_PAGE = 20;

    protected $pageService;
    protected $ressourceService;
    protected $layoutService;
    protected $localeService;
    /**
    * indexAction : Action index du controller de page
    *
    * @return ViewModel $viewModel 
    */
    public function listAction()
    {
        $pagesId = array();
        $ressourcesCollection = array();
        $this->layout()->setVariable('nav', "cms");
        $this->layout()->setVariable('subNav', "page");
        $p = $this->getRequest()->getQuery('page', 1);


        $pages = $this->getPageService()->getPageMapper()->findAll();
        
        $nbPage = count($pages);

        $pagesPaginator = new \Zend\Paginator\Paginator(new \Zend\Paginator\Adapter\ArrayAdapter($pages));
        $pagesPaginator->setItemCountPerPage(self::MAX_PER_PAGE);
        $pagesPaginator->setCurrentPageNumber($p);

        foreach ($pages as $page) {
            $pagesId[] = $page->getId();
        }

        $ressources = $this->getRessourceService()->getRessourceMapper()->findBy(array('model' => 'PlaygroundCMS\Entity\Page', 'recordId' => $pagesId));
        foreach ($ressources as $ressource) {
            $ressourcesCollection[$ressource->getRecordId()][$ressource->getLocale()] = $ressource;
        }

        return new ViewModel(array('pages'                => $pages,
                                   'pagesPaginator'       => $pagesPaginator,
                                   'nbPage'               => $nbPage,
                                   'ressourcesCollection' => $ressourcesCollection));
    }


    public function createAction()
    {
        $return  = array();
        $data = array();
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = array_merge(
                    $request->getPost()->toArray(),
                    $request->getFiles()->toArray()
            );
            $return = $this->getPageService()->checkPage($data);
            $data = $return["data"];
            unset($return["data"]);

            if ($return['status'] == 0) {
                $this->getPageService()->create($data);
                return $this->redirect()->toRoute('admin/playgroundcmsadmin/page');
            }
        }

        $credentials = Credential::$statusesForm;
        $pagesStatuses = Page::$statuses;
        $layouts = $this->getLayoutService()->getLayoutMapper()->findAll();
        $locales = $this->getLocaleService()->getLocaleMapper()->findBy(array('active_front' => 1));

        return new ViewModel(array('credentials'   => $credentials,
                                   'pagesStatuses' => $pagesStatuses,
                                   'layouts'       => $layouts,
                                   'locales'       => $locales,
                                   'data'          => $data,
                                   'return'        => $return));
    }

    public function editAction()
    {
        $return  = array();
        $data = array();
        
        $request = $this->getRequest();

        $pageId = $this->getEvent()->getRouteMatch()->getParam('id');
        $page = $this->getPageService()->getPageMapper()->findById($pageId);

        if(empty($page)){

            return $this->redirect()->toRoute('admin/playgroundcmsadmin/page');
        }

        $translations = $this->getPageService()->getPageMapper()->getEntityRepositoryForEntity($page->getTranslationRepository())->findTranslations($page);
        $page->setTranslations($translations);

        $ressources = $this->getRessourceService()->getRessourceMapper()->findBy(array('model' => 'PlaygroundCMS\Entity\Page', 'recordId' => $pageId));
        
        if ($request->isPost()) {
            $data = array_merge(
                    $request->getPost()->toArray(),
                    $request->getFiles()->toArray()
            );
            $return = $this->getPageService()->checkPage($data);
            $data = $return["data"];
            unset($return["data"]);

            if ($return['status'] == 0) {
                $this->getPageService()->edit($data);
                return $this->redirect()->toRoute('admin/playgroundcmsadmin/page');
            }
        }

        $credentials = Credential::$statusesForm;
        $pagesStatuses = Page::$statuses;
        $layouts = $this->getLayoutService()->getLayoutMapper()->findAll();
        $locales = $this->getLocaleService()->getLocaleMapper()->findBy(array('active_front' => 1));

        return new ViewModel(array('credentials'   => $credentials,
                                   'pagesStatuses' => $pagesStatuses,
                                   'layouts'       => $layouts,
                                   'locales'       => $locales,
                                   'page'          => $page,
                                   'ressources'     => $ressources,
                                   'return'        => $return));
    }

    public function removeAction()
    {
        $pageId = $this->getEvent()->getRouteMatch()->getParam('id');
        $page = $this->getPageService()->getPageMapper()->findById($pageId);

        if(empty($page)){

            return $this->redirect()->toRoute('admin/playgroundcmsadmin/page');
        }

        // Suppression des ressources associées 
        $ressources = $this->getRessourceService()->getRessourceMapper()->findBy(array('model' => 'PlaygroundCMS\Entity\Page', 'recordId' => $pageId));
        foreach ($ressources as $ressource) {
            $this->getRessourceService()->getRessourceMapper()->remove($ressource);
        }
        // Suppresion de la page
        $this->getPageService()->getPageMapper()->remove($page);

        return $this->redirect()->toRoute('admin/playgroundcmsadmin/page');
    }

    protected function getPageService()
    {
        if (!$this->pageService) {
            $this->pageService = $this->getServiceLocator()->get('playgroundcms_page_service');
        }

        return $this->pageService;
    }

    protected function getLocaleService()
    {
        if (!$this->localeService) {
            $this->localeService = $this->getServiceLocator()->get('playgroundcore_locale_service');
        }

        return $this->localeService;
    }

    protected function getLayoutService()
    {
        if (!$this->layoutService) {
            $this->layoutService = $this->getServiceLocator()->get('playgroundcms_layout_service');
        }

        return $this->layoutService;
    }

    protected function getRessourceService()
    {
        if (!$this->ressourceService) {
            $this->ressourceService = $this->getServiceLocator()->get('playgroundcms_ressource_service');
        }

        return $this->ressourceService;
    }
}
