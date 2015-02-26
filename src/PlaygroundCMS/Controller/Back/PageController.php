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
    /**
    * @var MAX_PER_PAGE  Nombre d'item par page
    */
    const MAX_PER_PAGE = 20;

    /**
    * @var Service $pageService Service de page
    */
    protected $pageService;

    /**
    * @var Ressource $ressourceService  Service de ressource
    */
    protected $ressourceService;
    
    /**
    * @var Layout $layoutService  Service de layout
    */
    protected $layoutService;
    
    /**
    * @var Locale $localeService  Service de locale
    */
    protected $localeService;
    
    /**
    * @var ModuleOptions $cmsOptions  Options de playgroundcms
    */
    protected $cmsOptions;

    /**
    * @var RevisionService revisionService  Service de Revision
    */
    protected $revisionService;

    /**
    * indexAction : Liste des pages
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

        $credentials = Credential::$statusesForm;
        $pagesStatuses = Page::$statuses;

        $files = $this->getLayoutService()->getLayouts();

        return new ViewModel(array('pages'                => $pages,
                                   'pagesPaginator'       => $pagesPaginator,
                                   'nbPage'               => $nbPage,
                                   'files'                => $files,
                                   'credentials'          => $credentials,
                                   'pagesStatuses'        => $pagesStatuses,
                                   'ressourcesCollection' => $ressourcesCollection));
    }

    /**
    * createAction : Creation de page
    *
    * @return ViewModel $viewModel 
    */
    public function createAction()
    {
        $return  = array();
        $data = array();
        $this->layout()->setVariable('nav', "cms");
        $this->layout()->setVariable('subNav', "page");

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = array_merge_recursive(
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

    /**
    * editAction : Edition d'une page en fonction d'un id
    * @param int $id : Id de la page
    *
    * @return ViewModel $viewModel 
    */
    public function editAction()
    {
        $return  = array();
        $data = array();
        
        $this->layout()->setVariable('nav', "cms");
        $this->layout()->setVariable('subNav', "page");
        
        $request = $this->getRequest();

        $pageId = $this->getEvent()->getRouteMatch()->getParam('id');
        $revisionId = $this->getEvent()->getRouteMatch()->getParam('revisionId', 0);

        $page = $this->getPageService()->getPageMapper()->findById($pageId);

        $filters = array('type' => get_class($page), 'objectId' => $page->getId());
        $revisions = $this->getRevisionService()->getRevisionMapper()->findByAndOrderBy($filters, array('id' => 'DESC'));

        if(empty($page)){

            return $this->redirect()->toRoute('admin/playgroundcmsadmin/page');
        }

        $translations = $this->getPageService()->getPageMapper()->getEntityRepositoryForEntity($page->getTranslationRepository())->findTranslations($page);
        $page->setTranslations($translations);

        if(!empty($revisionId)){
            $revision = $this->getRevisionService()->getRevisionMapper()->findById($revisionId);
            $page = unserialize($revision->getObject());
        }

        $ressources = $this->getRessourceService()->getRessourceMapper()->findBy(array('model' => 'PlaygroundCMS\Entity\Page', 'recordId' => $pageId));
        
        if ($request->isPost()) {
            $data = array_merge_recursive(
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
                                   'ressources'    => $ressources,
                                   'revisions'     => $revisions,
                                   'return'        => $return));
    }

    /**
    * removeAction : Edition d'une page en fonction d'un id
    * @param int $id : Id de la page
    *
    * @return ViewModel $viewModel 
    */
    
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

    /**
    * getPageService : Recuperation du service de page
    *
    * @return Page $pageService 
    */
    private function getPageService()
    {
        if (!$this->pageService) {
            $this->pageService = $this->getServiceLocator()->get('playgroundcms_page_service');
        }

        return $this->pageService;
    }

    /**
    * getLocaleService : Recuperation du service de locale
    *
    * @return Locale $localeService 
    */
    private function getLocaleService()
    {
        if (!$this->localeService) {
            $this->localeService = $this->getServiceLocator()->get('playgroundcore_locale_service');
        }

        return $this->localeService;
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
    * getRessourceService : Recuperation du service de Ressource
    *
    * @return Ressource $ressourceService 
    */
    private function getRessourceService()
    {
        if (!$this->ressourceService) {
            $this->ressourceService = $this->getServiceLocator()->get('playgroundcms_ressource_service');
        }

        return $this->ressourceService;
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
     * @return PageController $this
     */
    private function setRevisionService($revisionService)
    {
        $this->revisionService = $revisionService;

        return $this;
    }
}
